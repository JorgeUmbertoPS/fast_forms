<?php

namespace App\Models;

use App\Filament\Resources\EmbarqueUsersResource\Pages\EmbarqueListContainers;
use PgSql\Lob;

use App\Models\Oic;
use App\Models\Lote;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Traits\TraitLogs;
use App\Traits\TraitErros;
use App\Models\EmbarqueContainer;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use PhpParser\Builder\Param;

use function PHPUnit\Framework\directoryExists;

class Embarque extends Model
{
    use HasFactory, TraitLogs, TraitErros;
    //L - Liberado, B - Bloqueado, F - Finalizado
    const STATUS = [
        'Liberado' => 'L',
        'Bloqueado' => 'B',
        'Finalizado' => 'F',
    ];
   
    protected $primaryKey   = 'id';
    public $timestamps      = true;
    public $incrementing    = true;  
    protected $table        = 'embarques';
    protected $logName      = 'Embarques';

    protected $casts = [
        'em_edicao' => 'boolean',
    ];

    protected $fillable = [
        'id', 'cliente_id', 'transportadora_id', 'em_edicao_usu_id', 'contrato', 'data',
        'booking', 'navio', 'total_sacas', 'status_embarque', 'created_at', 'updated_at', 'oic', 'tipo', 'em_edicao',
        'embalagens'
    ];


    public function clientes(){
        return $this->hasOne(Cliente::class,'id','cliente_id');
    }

    public function transportadoras(){
        return $this->hasOne(Transportadora::class,'id','transportadora_id');
    }

    public function lotes_embarques(){
        return $this->hasMany(Lote::class);
    }

    public function oics_embarques(){
        return $this->hasMany(Oic::class);
    }    

    public function containers_embarques(){
        return $this->hasMany(EmbarqueContainer::class);
    }  
    
    public function user_edicao(){
        return $this->hasOne(User::class,'id','em_edicao_usu_id');
    }

    public static function total_containers(int $id){
        return EmbarqueContainer::where('embarque_id', $id)->count();
    }

    public static function total_containers_reprovados(int $id){
        return EmbarqueContainer::where('embarque_id', $id)->where('status', 2)->count();
    }

    public static function total_lotes(int $id){
        return Lote::where('embarque_id', $id)->count();
    }

    public static function total_oics(int $id){
        return Oic::where('embarque_id', $id)->count();
    }

    public static function BloquearParaEdicao($record){

        self::where('id', $record->id)->update([
            'em_edicao' => 1,
            'em_edicao_usu_id' => auth()->user()->id,
        ]);

        return array('status' => true, 'mensagem' => 'Embarque está bloqueado para Edição');
    }

    public static function LiberarBloqueioEdicao($record):array{
       
        if($record->em_edicao_usu_id > 0 && $record->em_edicao_usu_id != auth()->user()->id)
            return array('status' => false, 'mensagem' => 'Você não pode alterar o Status de Edição');

        self::where('id', $record->id)->update([
            'em_edicao' => 0,
            'em_edicao_usu_id' => null,
        ]);

        return array('status' => true, 'mensagem' => 'Liberado com Sucesso');
    }    

    public function tipo_desc(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => 'N',
            set: fn (string $value) => 'Novo',
        );
    }
    
    public static function LiberarParaChecklist(int $id){

        $embarque = Embarque::find($id);

        // so poderá gerar para embarques bloqueados
        if($embarque->status_embarque != 'B'){
            return array('status' => false, 'mensagem' => 'Não será possível gerar o Questionário para este Embarque');
        }

        if($embarque->em_edicao_usu_id > 0 && $embarque->em_edicao_usu_id != auth()->user()->id){
            return array('status' => false, 'mensagem' => 'Questionário está em Edição por outro usuário' );
        }

        $EmbarqueContainer = EmbarqueContainer::where('embarque_id', $id)->get();

        if($EmbarqueContainer->count() > 0){

            foreach($EmbarqueContainer as $emb_container){
            
                if($emb_container->questionario_id == 0){
                    return array('status' => false, 'mensagem' => 'Faltando Questionário no Container ' . $emb_container->descricao);
                }

                if($emb_container->lacracao_id == 0){
                    return array('status' => false, 'mensagem' => 'Faltando Lacração no Container ' . $emb_container->descricao);
                }                

                $modalidades_embarque = EmbarqueContainerModalidade::where('container_id', $emb_container->id)->get();

                if($modalidades_embarque->count() > 0){

                    foreach($modalidades_embarque as $modalidade){
                    
                        if($modalidade->modalidade_id == 0){
                            return array('status' => false, 'mensagem' => 'Faltando Modalidade no Container ' . $emb_container->descricao);
                        }
                    }
                }
                else{
                    return array('status' => false, 'mensagem' => 'Faltando Modalidade no Container ' . $emb_container->descricao);
                }
            }
        }    
        
        DB::beginTransaction();
        try {
            
            foreach($EmbarqueContainer as $emb_container){
            //'embarque_id',
            //'questionario_id',
                if($emb_container->questionario_id > 0){
                    
                    $questionario = Questionario::where('id', $emb_container->questionario_id)->first();
                    $questionario_perguntas = QuestionarioPerguntas::where('questionario_id', $questionario->id)->orderBy('sequencia')->get();
                                        
                    if($questionario_perguntas->count() > 0){                       
                        $i = 1;
                        foreach($questionario_perguntas as $quest_pergunta){

                            EmbarqueContainerChecklistResposta::insert([
                                'embarques_containers_id' => $emb_container->id,
                                'pergunta'                => $quest_pergunta->pergunta,
                                'texto'                   => $quest_pergunta->texto,
                                'questionario_id'         => $questionario->id,
                                'questionario_pergunta_id'=> $quest_pergunta->id,
                                'embarque_id'             => $id,
                                'pergunta_imagem'         => $quest_pergunta->pergunta_imagem,
                                'sequencia'               => $quest_pergunta->sequencia,
                                'pergunta_dependente_id'  => $quest_pergunta->pergunta_dependente_id,
                                'visivel'                 => $quest_pergunta->pergunta_dependente_id > 0 ? 'N' : 'S',
                                'pergunta_neutra'         => $quest_pergunta->pergunta_neutra,
                                'pergunta_finaliza_negativa' => $quest_pergunta->pergunta_finaliza_negativa
                            ]);
                            $i++;
                        }
                    }
                
                    $modalidades_embarques = EmbarqueContainerModalidade::where('container_id', $emb_container->id)->orderBy('id')->get();

                    if($modalidades_embarques->count() > 0){
                        $i = 1;
                        foreach($modalidades_embarques as $modalidade_embarque){
                         
                            $modalidades_roteiros = ModalidadeRoteiro::where('modalidade_id', $modalidade_embarque->modalidade_id)->orderBy('sequencia')->get();

                            if($modalidades_roteiros->count() > 0){

                                foreach($modalidades_roteiros as $modalidade_roteiro){

                                    //consulta uma EmbarqueContainerModalidadeResposta pela descricao para ver se já existe
                                    $descricao = EmbarqueContainerModalidadeResposta::where('descricao', $modalidade_roteiro->descricao)
                                                                                ->where('embarque_id', $id)
                                                                                ->where('embarques_containers_id', $emb_container->id)
                                                                                ->first();

                                    if($descricao == null)
                                    {
                                        EmbarqueContainerModalidadeResposta::insert([
                                            'embarques_containers_id' => $emb_container->id,
                                            'descricao'               => $modalidade_roteiro->descricao,
                                            'texto'                   => $modalidade_roteiro->texto,
                                            'modalidade_id'           => $modalidade_embarque->modalidade_id,
                                            'modalidade_roteiro_id'   => $modalidade_roteiro->id,
                                            'embarque_id'             => $id,
                                            'sequencia'               => $modalidade_roteiro->sequencia,
                                            'pergunta_neutra'         => $modalidade_roteiro->pergunta_neutra,
                                            'pergunta_finaliza_negativa' => $modalidade_roteiro->pergunta_finaliza_negativa
                                        ]);
                                        $i++;
                                    }
                                }
                            }
                        }
                    }

                    $lacracao = Lacracao::where('id', $emb_container->lacracao_id)->first();
                    $lacracao_perguntas = LacracaoRoteiro::where('lacracao_id', $lacracao->id)->orderBy('sequencia')->get();
                                        
                    if($lacracao_perguntas->count() > 0){                       
                        $i = 1;
                        foreach($lacracao_perguntas as $lacre_pergunta){

                            EmbarqueContainerLacracaoResposta::insert([
                                'embarques_containers_id'   => $emb_container->id,
                                'descricao'                 => $lacre_pergunta->descricao,
                                'texto'                     => $lacre_pergunta->texto,
                                'lacracao_id'               => $lacracao->id,
                                'lacracao_roteiro_id'       => $lacre_pergunta->id,
                                'embarque_id'               => $id,
                                'sequencia'                 => $lacre_pergunta->sequencia,
                                'pergunta_neutra'           => $lacre_pergunta->pergunta_neutra,
                                'pergunta_finaliza_negativa' => $lacre_pergunta->pergunta_finaliza_negativa
                            ]);
                            $i++;
                        }
                    }
                }
            } 
            $embarque->status_embarque = 'L';
            $embarque->em_edicao = 0;
            $embarque->em_edicao_usu_id = null;

            $embarque->save();
            
            DB::commit();
            return array('status' => true, 'mensagem' => 'Gerado com Sucesso');
        } catch (\Throwable $th) {
            DB::rollBack();

            return array('status' => false, 'mensagem' => substr($th->getMessage(),0, 500));
        }
        
    }   

    public static function EmbarqueFinalizado(int $id):bool{

        // Verificar se o campo status_embarque está com o valor 'F'
        $embarque = self::find($id);

        if($embarque->status_embarque == 'F')
            return true;
        else
            return false;

    }
    
    public static function TotalQuestionario(int $id):int{
        $retorno = EmbarqueContainerChecklistResposta::where('embarque_id', $id)->count();
        return $retorno;
    }

    public static function TotalModalidades(int $id):int{
        $retorno = EmbarqueContainerModalidadeResposta::where('embarque_id', $id)->count();
        return $retorno;
    }

    public static function TotalGeralItens(int $id):int{
        return self::TotalModalidades($id) + self::TotalQuestionario($id);
    }

    public static function TotalQuestionariosAbertos(int $id):int{

        return EmbarqueContainerChecklistResposta::where('embarque_id', $id)
                                                    ->whereNull('resposta')
                                                    ->count();                                                  
    }

    public static function TotalModalidadesAbertas(int $id):int{

        return EmbarqueContainerModalidadeResposta::where('embarque_id', $id)
                                                    ->whereNull('imagem')
                                                    ->count();                                                    

    }    

    public static function TotalGeralItensAbertos(int $id):int{
        return self::TotalQuestionariosAbertos($id) + self::TotalModalidadesAbertas($id);
    }

    public static function GerarPdf($id){

        // Recuperar os registros do banco dados


        $embarques = Embarque::where('id', $id)->with('clientes')->with('transportadoras')->first();
        $containers = EmbarqueContainer::where('embarque_id', $id)->get();

        $pdf = PDF::loadView('embarques.gerar-pdf', [
            'embarques' => [$embarques],
            'containers' => [$containers],
            'foto_width' => ParametroSistema::getFotoWidth(), 
            'foto_height' => ParametroSistema::getFotoHeight(),
        ])->setPaper('A4', 'portrait');
    
        $file = md5(now()). '.pdf';

        $embarque = Embarque::find($id);
        $embarque->arquivo_pdf = $file;
        $embarque->save();

        //$pdf->save(storage_path("app/public/arquivos_pdf/".$file));
        $pdf->save(storage_path("app\public\arquivos_pdf\\".$file));
        
    }    

    public static function DownloadPdf($id){

        // Recuperar os registros do banco dados

        $embarques = Embarque::where('id', $id)->with('clientes')->with('transportadoras')->first();
        $containers = EmbarqueContainer::where('embarque_id', $id)->get();

        $pdf = PDF::loadView('embarques.gerar-pdf', [
            'embarques' => [$embarques],
            'containers' => [$containers],
            'foto_width' => ParametroSistema::getFotoWidth(), 
            'foto_height' => ParametroSistema::getFotoHeight(),
        ])->setPaper('A4', 'portrait');

        //usar o loadFile para buscar o arquivo pdf no diretório
       // $pdf = PDF::loadFile(storage_path("arquivos_pdf\\".$embarques->arquivo_pdf));

        return $pdf;
            
    }  
    
    public static function PodeAtualizarEmbarqueFinalizado($record):bool{

        // se o usuario for root, pode atualizar estando liberado ou não
        if(auth()->user()->isRoot())
            return true;

        // se o status do embarque for finalizado e o usuário não for root, não pode atualizar
        return !($record['status_embarque'] === (Embarque::STATUS['Finalizado']) && auth()->user()->isRoot() == false);
        
        // se o status do embarque for liberado e o usuário não for root, pode atualizar
        return ($record['status_embarque'] === (Embarque::STATUS['Liberado']) && auth()->user()->isRoot() == false);

        
        //($record['status_embarque'] === (Embarque::STATUS['Liberado']) && auth()->user()->role('root') == false)
    }

    //pode Gerar PDF
    public static function PodeGerarPDFEmbarque($record):bool{

        // se o usuario for root, pode gerar pdf estando liberado ou não
        if(auth()->user()->isRoot())
            return true;

        // se o embarque nao estiver liberado, nao pode gerar pdf
        return !($record['status_embarque'] === (Embarque::STATUS['Liberado']));

        return true;



    }

    // EmAndamento - Embarques que estão com Status L 
    public static function EmAndamento(){
        $embarques = self::where('status_embarque', 'L')->count();
        return $embarques;
    }

    public static function Finalizados(){
        $embarques = self::where('status_embarque', 'F')->count(); 
        return $embarques;
    }

    // quantidade anual de embaques criados. 
    // retorna um array com a quantidade de embarques criados por mês
    public static function QuantidadeAnual(){

        $retorno = [];
        for($i = 1; $i <= 12; $i++){
            $embarque = self::select(DB::raw('count(id) as total'), DB::raw('EXTRACT(MONTH from data) as mes'))
            ->whereYear('data', date('Y'))
            ->whereMonth('data', $i)
            ->groupBy('mes')
            ->first();
            $mes_str = Utils::ObterMesResumido($i);
            $retorno[$mes_str] = $embarque->total ?? 0;
        }

        return $retorno;

    }
}
