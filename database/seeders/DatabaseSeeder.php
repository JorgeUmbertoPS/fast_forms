<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Artisan;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {  

        DB::table('roles')->insert(['name' => 'root',  'guard_name' => 'web']);
        DB::table('roles')->insert(['name' => 'admin', 'guard_name' => 'web']);
        DB::table('roles')->insert(['name' => 'users',  'guard_name' => 'web']);

        Artisan::call('permissions:sync');
        
        DB::table('model_has_roles')->insert(['role_id' => '1', 'model_type' => 'App\Models\User', 'model_id' => '1']);
        DB::table('model_has_roles')->insert(['role_id' => '3', 'model_type' => 'App\Models\User', 'model_id' => '2']);
        DB::table('model_has_roles')->insert(['role_id' => '3', 'model_type' => 'App\Models\User', 'model_id' => '3']);
        DB::table('model_has_roles')->insert(['role_id' => '3', 'model_type' => 'App\Models\User', 'model_id' => '4']);
        DB::table('model_has_roles')->insert(['role_id' => '2', 'model_type' => 'App\Models\User', 'model_id' => '5']);
        DB::table('model_has_roles')->insert(['role_id' => '2', 'model_type' => 'App\Models\User', 'model_id' => '6']);
        DB::table('model_has_roles')->insert(['role_id' => '2', 'model_type' => 'App\Models\User', 'model_id' => '7']);
        DB::table('model_has_roles')->insert(['role_id' => '3', 'model_type' => 'App\Models\User', 'model_id' => '8']);
        DB::table('model_has_roles')->insert(['role_id' => '3', 'model_type' => 'App\Models\User', 'model_id' => '9']);
        DB::table('model_has_roles')->insert(['role_id' => '3', 'model_type' => 'App\Models\User', 'model_id' => '10']);
        DB::table('model_has_roles')->insert(['role_id' => '3', 'model_type' => 'App\Models\User', 'model_id' => '11']);
        DB::table('model_has_roles')->insert(['role_id' => '3', 'model_type' => 'App\Models\User', 'model_id' => '12']);        
        DB::table('model_has_roles')->insert(['role_id' => '3', 'model_type' => 'App\Models\User', 'model_id' => '13']);
        DB::table('model_has_roles')->insert(['role_id' => '3', 'model_type' => 'App\Models\User', 'model_id' => '14']);
        DB::table('model_has_roles')->insert(['role_id' => '3', 'model_type' => 'App\Models\User', 'model_id' => '15']);
        DB::table('model_has_roles')->insert(['role_id' => '3', 'model_type' => 'App\Models\User', 'model_id' => '16']);
        DB::table('model_has_roles')->insert(['role_id' => '2', 'model_type' => 'App\Models\User', 'model_id' => '17']);
        DB::table('model_has_roles')->insert(['role_id' => '2', 'model_type' => 'App\Models\User', 'model_id' => '18']);
        DB::table('model_has_roles')->insert(['role_id' => '2', 'model_type' => 'App\Models\User', 'model_id' => '19']);
        DB::table('model_has_roles')->insert(['role_id' => '2', 'model_type' => 'App\Models\User', 'model_id' => '20']);
        DB::table('model_has_roles')->insert(['role_id' => '3', 'model_type' => 'App\Models\User', 'model_id' => '21']);
        DB::table('model_has_roles')->insert(['role_id' => '2', 'model_type' => 'App\Models\User', 'model_id' => '22']);
        DB::table('model_has_roles')->insert(['role_id' => '3', 'model_type' => 'App\Models\User', 'model_id' => '23']);
        DB::table('model_has_roles')->insert(['role_id' => '3', 'model_type' => 'App\Models\User', 'model_id' => '24']);     
        
        DB::table('setores')->insert(['nome'=> 'Balança']); //1
        DB::table('setores')->insert(['nome'=> 'Classificação']); //2
        DB::table('setores')->insert(['nome'=> 'Conferente']);//3
        DB::table('setores')->insert(['nome'=> 'Diretoria']);//4
        DB::table('setores')->insert(['nome'=> 'Estoque']);//5
        DB::table('setores')->insert(['nome'=> 'Gerência de Operações']);//6
        DB::select("select setval('setores_id_seq', (select max(id) from setores))");

        DB::table('users')->insert([
            "name" => "SuperAdmin",
            "login" => 'super.admin', 
            'email' => "",
            'email_verified_at' => null,
            "password" => '$2y$10$7/C.sR2IBJFpePHHDwiyMO0ABXjPUn8Sbu7y6Gw1VjLBNdsRRH9z6',
            'empresa_id' => 1,
            'ativo' => 1,
            'admin' => -1,
            'remember_token' => NULL,
            'created_at' => Date("Y-m-d H:i:s"),
            'updated_at' => Date("Y-m-d H:i:s"),
        ]);

        User::insert(['name' => 'Gerson Pires da Fonseca Filho', 'setor_id' => 1, 'password' => Hash::make('1234'), 'empresa_id' => 1, 'login' => 'gerson.pires', 'admin' => 0]);
        User::insert(['name' => 'Luan Vitor Germano', 'setor_id' => 1, 'password' => Hash::make('1234'), 'empresa_id' => 1, 'login' => 'luan.germano', 'admin' => 0]);
        User::insert(['name' => 'Arthur Augusto dos Santos', 'setor_id' => 2, 'password' => Hash::make('1234'), 'empresa_id' => 1, 'login' => 'arthur.augusto', 'admin' => 0]);
        User::insert(['name' => 'Iara Canevare', 'setor_id' => 2, 'password' => Hash::make('1234'), 'empresa_id' => 1, 'login' => 'iara.canevare', 'admin' => 1]);
        User::insert(['name' => 'Patrícia Pariz', 'setor_id' => 2, 'password' => Hash::make('1234'), 'empresa_id' => 1, 'login' => 'patricia.pariz', 'admin' => 1]);
        User::insert(['name' => 'Romeu Marchi Hein', 'setor_id' => 2, 'password' => Hash::make('1234'), 'empresa_id' => 1, 'login' => 'romeu.marchi', 'admin' => 1]);
        User::insert(['name' => 'Alef de Oliveira Santana', 'setor_id' => 3, 'password' => Hash::make('1234'), 'empresa_id' => 1, 'login' => 'alef.oliveira', 'admin' => 0]);
        User::insert(['name' => 'Ítalo Henrique Santiago', 'setor_id' => 3, 'password' => Hash::make('1234'), 'empresa_id' => 1, 'login' => 'italo.henrique', 'admin' => 0]);
        User::insert(['name' => 'João Vilanez de Souza', 'setor_id' => 3, 'password' => Hash::make('1234'), 'empresa_id' => 1, 'login' => 'joao.vilanez', 'admin' => 0]);
        User::insert(['name' => 'Johnny Cruz de Araujo', 'setor_id' => 3, 'password' => Hash::make('1234'), 'empresa_id' => 1, 'login' => 'johnny.cruz', 'admin' => 0]);
        User::insert(['name' => 'Paulo Sérgio Rodrigues Filho', 'setor_id' => 3, 'password' => Hash::make('1234'), 'empresa_id' => 1, 'login' => 'paulo.sergio', 'admin' => 0]);
        User::insert(['name' => 'Reginaldo dos Santos Cruz', 'setor_id' => 3, 'password' => Hash::make('1234'), 'empresa_id' => 1, 'login' => 'reginaldo.santos', 'admin' => 0]);
        User::insert(['name' => 'Reinaldo Luis G. dos Santos', 'setor_id' => 3, 'password' => Hash::make('1234'), 'empresa_id' => 1, 'login' => 'reinaldo.luis', 'admin' => 0]);
        User::insert(['name' => 'Wellington Fabiano Gutzlaf Viana', 'setor_id' => 3, 'password' => Hash::make('1234'), 'empresa_id' => 1, 'login' => 'wellington.fabiano', 'admin' => 0]);
        User::insert(['name' => 'Wellington Mariano Monteiro', 'setor_id' => 3, 'password' => Hash::make('1234'), 'empresa_id' => 1, 'login' => 'wellington.mariano', 'admin' => 0]);
        User::insert(['name' => 'Adriano Silva Leme', 'setor_id' => 4, 'password' => Hash::make('1234'), 'empresa_id' => 1, 'login' => 'adriano.leme', 'admin' => 1]);
        User::insert(['name' => 'Guilherme Rodrigues Zenker Leme', 'setor_id' => 4, 'password' => Hash::make('1234'), 'empresa_id' => 1, 'login' => 'guilherme.leme', 'admin' => 1]);
        User::insert(['name' => 'Plínio Tadeu Zenker Leme', 'setor_id' => 4, 'password' => Hash::make('1234'), 'empresa_id' => 1, 'login' => 'tadeu.leme', 'admin' => 1]);
        User::insert(['name' => 'Alisson Milton Luiz', 'setor_id' => 5, 'password' => Hash::make('1234'), 'empresa_id' => 1, 'login' => 'alisson.luiz', 'admin' => 1]);
        User::insert(['name' => 'Tiago Benedito de Oliveira', 'setor_id' => 5, 'password' => Hash::make('1234'), 'empresa_id' => 1, 'login' => 'tiago.benedito', 'admin' => 0]);
        User::insert(['name' => 'Valéria Cristina Pontes Furtado Bento', 'setor_id' => 5, 'password' => Hash::make('1234'), 'empresa_id' => 1, 'login' => 'valeria.pontes', 'admin' => 1]);
        User::insert(['name' => 'Erivelton do Carmo Alves', 'setor_id' => 5, 'password' => Hash::make('1234'), 'empresa_id' => 1, 'login' => 'erivelton.alves', 'admin' => 0]);
        User::insert(['name' => 'Alex da Silva Parada', 'setor_id' => 6, 'password' => Hash::make('1234'), 'empresa_id' => 1, 'login' => 'alex.silva', 'admin' => 0]);
        DB::select("select setval('users_id_seq', (select max(id) from users))");

        DB::table('clientes')->insert(['nome' => trim('ADOLPHO MELLÃO CECCHI E OUTROS          ')]);
        DB::table('clientes')->insert(['nome' => trim('ALEANDRO ROGERIO TROMBIN                               ')]);
        DB::table('clientes')->insert(['nome' => trim('ALESSANDRA DA TRINDADE LOPES                           ')]);
        DB::table('clientes')->insert(['nome' => trim('ANA MARIA VERONEZE BEIRA E OUTROS                      ')]);
        DB::table('clientes')->insert(['nome' => trim('ANDRE AUGUSTO PEREIRA LIMA E OUTRAS                    ')]);
        DB::table('clientes')->insert(['nome' => trim('ANTONIO BELTRAN MARTINEZ                               ')]);
        DB::table('clientes')->insert(['nome' => trim('ARNALDO ALVES VIEIRA                                   ')]);
        DB::table('clientes')->insert(['nome' => trim('ATLANTICA EXPORTAÇÃO E IMPORTAÇÃO S/A.                 ')]);
        DB::table('clientes')->insert(['nome' => trim('BLEND COFFEE COMERCIO EXPORTAÇÃO E IMPORTAÇÃO LTDA     ')]);
        DB::table('clientes')->insert(['nome' => trim('BOURBON SPECIALTY COFFEES S/A                          ')]);
        DB::table('clientes')->insert(['nome' => trim('BRASIL ESPRESSO COMERCIO ATACADISTA LTDA.              ')]);
        DB::table('clientes')->insert(['nome' => trim('BRAULIO FABIANO                                        ')]);
        DB::table('clientes')->insert(['nome' => trim('BUENO CAFE COMERCIO E EXPORTAÇÃO EIRELI                ')]);
        DB::table('clientes')->insert(['nome' => trim('CAFE QUETAL INDUSTRIA E COMERCIO EIRELI                ')]);
        DB::table('clientes')->insert(['nome' => trim('CAFE TOLEDO LTDA.                                      ')]);
        DB::table('clientes')->insert(['nome' => trim('CAFEBRAS COMERCIO DE CAFES DO BRASIL S/A               ')]);
        DB::table('clientes')->insert(['nome' => trim('CAFELANDIA AGROMERCANTIL LTDA - EPP                    ')]);
        DB::table('clientes')->insert(['nome' => trim('CAIENA AGR COMERCIO DE CEREAIS S.A                     ')]);
        DB::table('clientes')->insert(['nome' => trim('CAPAL COOPERATIVA AGROINDUSTRIAL                       ')]);
        DB::table('clientes')->insert(['nome' => trim('COCAPEC -COOPERATIVA DE CAFEICULTORES E AGROPECUARISTAS')]);
        DB::table('clientes')->insert(['nome' => trim('COFCO BRASIL S.A                                       ')]);
        DB::table('clientes')->insert(['nome' => trim('COFFEA IMPORTAÇÃO, EXPORTAÇÃO DE CAFÉ -EIRELI          ')]);
        DB::table('clientes')->insert(['nome' => trim('COFFEE SENSES LTDA                                     ')]);
        DB::table('clientes')->insert(['nome' => trim('COMEXIM LTDA                                           ')]);
        DB::table('clientes')->insert(['nome' => trim('COOPERATIVA REGIONAL DE CAFEICULTORES EM GUAXUPE LTDA  ')]);
        DB::table('clientes')->insert(['nome' => trim('COOPERCITRUS  COOPERATIVA DE PRODUTORES RURAIS         ')]);
        DB::table('clientes')->insert(['nome' => trim('CULTUR COMERCIAL DE GRÃOS LTDA                         ')]);
        DB::table('clientes')->insert(['nome' => trim('DOLCE COFFEE COMERCIO E EXPORTACAO DE CAFE LTDA.       ')]);
        DB::table('clientes')->insert(['nome' => trim('ED & F MAN BRASIL SA                                   ')]);
        DB::table('clientes')->insert(['nome' => trim('EISA EMPRESA INTERAGRICOLA S/A                         ')]);
        DB::table('clientes')->insert(['nome' => trim('EXPERIMENTAL AGRICOLA DO BRASIL LTDA                   ')]);
        DB::table('clientes')->insert(['nome' => trim('EXPORTADORA CAPRICORNIO COFFEES LTDA                   ')]);
        DB::table('clientes')->insert(['nome' => trim('EXPORTADORA DE CAFE GUAXUPE LTDA                       ')]);
        DB::table('clientes')->insert(['nome' => trim('EXPRESSO PINHAL LTDA                                   ')]);
        DB::table('clientes')->insert(['nome' => trim('FARMLY DO BRASIL IMPORTAÇÃO E EXPORTAÇÃO LTDA          ')]);
        DB::table('clientes')->insert(['nome' => trim('FAZENDA EMPYREO S/A                                    ')]);
        DB::table('clientes')->insert(['nome' => trim('FMR CAFES COM.IMPORTACAO E EXPORTACAO LTDA             ')]);
        DB::table('clientes')->insert(['nome' => trim('FORTALEZA AGRO MERCANTIL LTDA                          ')]);
        DB::table('clientes')->insert(['nome' => trim('FRANCISCO FONSECA FIGUEIREDO                           ')]);
        DB::table('clientes')->insert(['nome' => trim('FRANCISCO MALTA CARDOZO                                ')]);
        DB::table('clientes')->insert(['nome' => trim('GARÇA ARMAZENS EIRELI-EPP                              ')]);
        DB::table('clientes')->insert(['nome' => trim('GRAMCERRI COMERCIO EXPORTAÇÃO IMPORTAÇÃO DE CAFE LTDA  ')]);
        DB::table('clientes')->insert(['nome' => trim('GRANCHELLI ALIMENTAÇÃO LTDA - ME                       ')]);
        DB::table('clientes')->insert(['nome' => trim('GRANO TRADING EXPORTADORA E IMPORTADORA LTDA.          ')]);
        DB::table('clientes')->insert(['nome' => trim('HOMERO TEIXEIRA DE MACEDO JUNIOR                       ')]);
        DB::table('clientes')->insert(['nome' => trim('JACOBS DOUWE EGBERTS BR COMERC DE CAFES LTDA           ')]);
        DB::table('clientes')->insert(['nome' => trim('JOAO ANTONIO LIAN E OUTRO                              ')]);
        DB::table('clientes')->insert(['nome' => trim('JOAO LUIZ COBRA MONTEIRO                               ')]);
        DB::table('clientes')->insert(['nome' => trim('KHALDOUN IMP.EXP.PROD.ALIM.LTDA                        ')]);
        DB::table('clientes')->insert(['nome' => trim('LOUIS DREYFUS COMPANY BRASIL S.A.                      ')]);
        DB::table('clientes')->insert(['nome' => trim('MARCO ANTONIO GUARDABAXO                               ')]);
        DB::table('clientes')->insert(['nome' => trim('MARIA JOSE BRUNO SERAFIM                               ')]);
        DB::table('clientes')->insert(['nome' => trim('MARIA ROSA DANELON MING                                ')]);
        DB::table('clientes')->insert(['nome' => trim('MARINA DINIZ JUNQUEIRA                                 ')]);
        DB::table('clientes')->insert(['nome' => trim('MC COFFE DO BRASIL LTDA                                ')]);
        DB::table('clientes')->insert(['nome' => trim('MCC SPECIALTY COFFEE EXPORTADORA LTDA                  ')]);
        DB::table('clientes')->insert(['nome' => trim('MERCON BRASIL COMERCIO DE CAFE LTDA                    ')]);
        DB::table('clientes')->insert(['nome' => trim('MITSUI & CO.COFFEE TRADING (BRAZIL) LTDA               ')]);
        DB::table('clientes')->insert(['nome' => trim('MITUAKI SHIGUENO                                       ')]);
        DB::table('clientes')->insert(['nome' => trim('NKG STOCKLER LTDA                                      ')]);
        DB::table('clientes')->insert(['nome' => trim('NUTRADE COML EXPORT LTDA                               ')]);
        DB::table('clientes')->insert(['nome' => trim('OCAFI IMPORTAÇÃO E EXPORTAÇÃO LTDA                     ')]);
        DB::table('clientes')->insert(['nome' => trim('OLAM AGRICOLA LTDA                                     ')]);
        DB::table('clientes')->insert(['nome' => trim('PEDRO A. RIBEIRO NETO E OUTROS                         ')]);
        DB::table('clientes')->insert(['nome' => trim('PEDRO ALCANTARA RIBEIRO NETO                           ')]);
        DB::table('clientes')->insert(['nome' => trim('PRATAPEREIRA COMERCIO IMPORTACAO E EXPORTACAO DE CAFE L')]);
        DB::table('clientes')->insert(['nome' => trim('RB COFFEES LTDA.                                       ')]);
        DB::table('clientes')->insert(['nome' => trim('ROBSON ANESIO LOPES                                    ')]);
        DB::table('clientes')->insert(['nome' => trim('SERGIO HENRIQUE NUNES                                  ')]);
        DB::table('clientes')->insert(['nome' => trim('SMC - COMERCIAL E EXPORTADORA DE CAFÉ                  ')]);
        DB::table('clientes')->insert(['nome' => trim('STOCKLER COMERCIAL E EXPORTADORA LTDA                  ')]);
        DB::table('clientes')->insert(['nome' => trim('SUCAFINA BRASIL, INDUSTRIA, COMERCIO E EXPORTAÇÃO LTDA.')]);
        DB::table('clientes')->insert(['nome' => trim('TANGARA IMPORTADORA E EXPORTADORA S/A                  ')]);
        DB::table('clientes')->insert(['nome' => trim('TORRES PERES COMERCIAL LTDA ME                         ')]);
        DB::table('clientes')->insert(['nome' => trim('TRISTAO CIA DE COMERCIO EXTERIOR                       ')]);
        DB::table('clientes')->insert(['nome' => trim('TRUST COMERCIO DE GRÃOS E SERVIÇOS ADMINISTRATIVOS LTDA')]);
        DB::table('clientes')->insert(['nome' => trim('UNION TRADING COMERCIO,IMPORTACAO E EXPORTACAO LTDA    ')]);
        DB::table('clientes')->insert(['nome' => trim('URSULA FRIEDA SELMA BOSENBERG E OUTRO                  ')]);
        DB::table('clientes')->insert(['nome' => trim('USINA SAO FRANCISCO S/A                                ')]);
        DB::table('clientes')->insert(['nome' => trim('VA&E TRADING DO BRASIL LTDA                            ')]);
        DB::table('clientes')->insert(['nome' => trim('VANESSA DE FIGUEIREDO FERREIRA                         ')]);
        DB::table('clientes')->insert(['nome' => trim('VERA SALOMAO MALTA CARDOZO E OUTROS                    ')]);
        DB::table('clientes')->insert(['nome' => trim('VERDES GRAO TRANSPORTES RODOVIARIOS                    ')]);
        DB::table('clientes')->insert(['nome' => trim('VOITER COMERCIO DE CEREAIS LTDA                        ')]);
        DB::table('clientes')->insert(['nome' => trim('VOLCAFE LTDA (SANTOS)                                  ')]);
        DB::table('clientes')->insert(['nome' => trim('WHITE COMMODITIES AGRICOLAS LTDA                       ')]);
        DB::select("select setval('clientes_id_seq', (select max(id) from clientes))");

        DB::table('transportadoras')->insert(['nome' => trim('ABC CARGAS LTDA                                        ')]);
        DB::table('transportadoras')->insert(['nome' => trim('ALTOEXPRESS CARGAS E ENCOMENDAS LTDA                   ')]);
        DB::table('transportadoras')->insert(['nome' => trim('ATMA LOGISTICA E TRANSPORTES LTDA                      ')]);
        DB::table('transportadoras')->insert(['nome' => trim('ATMA TRANSPORTES LTDA                                  ')]);
        DB::table('transportadoras')->insert(['nome' => trim('BERALDI LOGISTICA LTDA-EPP.                            ')]);
        DB::table('transportadoras')->insert(['nome' => trim('BIAZOTO LOGISTICA & TRANSPORTES                        ')]);
        DB::table('transportadoras')->insert(['nome' => trim('BIAZOTO SOLUCOES EM TRANSPORTES LTDA                   ')]);
        DB::table('transportadoras')->insert(['nome' => trim('CARGO NOW LOGISTICA E TRANSPORTE LTDA                  ')]);
        DB::table('transportadoras')->insert(['nome' => trim('CMJ TRANSPORTES E LOGISTICA DO BRASIL                  ')]);
        DB::table('transportadoras')->insert(['nome' => trim('COOP. DOS CAMINHONEIROS DE T. PONTAS LTDA              ')]);
        DB::table('transportadoras')->insert(['nome' => trim('COOP. DOS TRANSPORTADORES AUTONOMOS DE VARGINHA        ')]);
        DB::table('transportadoras')->insert(['nome' => trim('COOTRANS COOP TRANSP DE CARGAS                         ')]);
        DB::table('transportadoras')->insert(['nome' => trim('COSER TRANSPORTES LTDA                                 ')]);
        DB::table('transportadoras')->insert(['nome' => trim('COTRAG-COOP DOS TRANSP ROD CARGAS GARÇA                ')]);
        DB::table('transportadoras')->insert(['nome' => trim('COTRANSP LTDA.                                         ')]);
        DB::table('transportadoras')->insert(['nome' => trim('CRZ DISTRIBUIDORA E TRANSPORTADORA LTDA.               ')]);
        DB::table('transportadoras')->insert(['nome' => trim('CTM-COMERCIO E TRANSPORTES MATIELO LTDA                ')]);
        DB::table('transportadoras')->insert(['nome' => trim('EMPRESA DE TRANSPORTES COVRE LTDA                      ')]);
        DB::table('transportadoras')->insert(['nome' => trim('EMPRESA DE TRANSPORTES RAMOS LTDA.                     ')]);
        DB::table('transportadoras')->insert(['nome' => trim('EURO JALES TRANSPORTES LTDA                            ')]);
        DB::table('transportadoras')->insert(['nome' => trim('FH MARTINS TRANSPORTES PARAISO LTDA                    ')]);
        DB::table('transportadoras')->insert(['nome' => trim('FIEZA TRANSPORTES LTDA - EPP                           ')]);
        DB::table('transportadoras')->insert(['nome' => trim('GLOBAL LOGISTICS REPRESENTAÇÕES LTDA                   ')]);
        DB::table('transportadoras')->insert(['nome' => trim('GRAO DE MINAS LOGISTICA E TRANSPORTES LTDA             ')]);
        DB::table('transportadoras')->insert(['nome' => trim('GRAO DO CERRADO TRANSPORTES LTDA.                      ')]);
        DB::table('transportadoras')->insert(['nome' => trim('GRUPO TRANSCARGO INTERNACIONAL                         ')]);
        DB::table('transportadoras')->insert(['nome' => trim('GT MINAS TRANSPORTES                                   ')]);
        DB::table('transportadoras')->insert(['nome' => trim('ICATU TRANSPORTES LTDA                                 ')]);
        DB::table('transportadoras')->insert(['nome' => trim('J. JUNIOR TRANSPORTES RODOVIARIOS LTDA                 ')]);
        DB::table('transportadoras')->insert(['nome' => trim('JADLOG LOGISTICA S.A.                                  ')]);
        DB::table('transportadoras')->insert(['nome' => trim('LG TRANSPORTES E LOGISTICA LTDA                        ')]);
        DB::table('transportadoras')->insert(['nome' => trim('LIMA E PAES COMERCIO E TRANSPORTE LTDA.                ')]);
        DB::table('transportadoras')->insert(['nome' => trim('LOGISTICA RIO PARDO                                    ')]);
        DB::table('transportadoras')->insert(['nome' => trim('NELCAR TRANSPORTES                                     ')]);
        DB::table('transportadoras')->insert(['nome' => trim('NOVA SAFRA TRANSPORTES LTDA                            ')]);
        DB::table('transportadoras')->insert(['nome' => trim('OASIS TRANSPORTES E LOGISTICA                          ')]);
        DB::table('transportadoras')->insert(['nome' => trim('RABELO FRANCA TRANSPORTES EIRELI - ME                  ')]);
        DB::table('transportadoras')->insert(['nome' => trim('RISSO EXPRESS TRANSP. DE CARGAS LTDA-EPP               ')]);
        DB::table('transportadoras')->insert(['nome' => trim('RL ASSI TRANSPORTES LTDA-ME                            ')]);
        DB::table('transportadoras')->insert(['nome' => trim('RM CARGO TRANSPORTES E LOGÍSTICA LTDA                  ')]);
        DB::table('transportadoras')->insert(['nome' => trim('ROCAPEL TRANSPORTES LTDA                               ')]);
        DB::table('transportadoras')->insert(['nome' => trim('RODONAVES TRANSPORTES E ENCOMENDAS LTDA                ')]);
        DB::table('transportadoras')->insert(['nome' => trim('SAMID TRANSPORTES E LOGISTICA LTDA                     ')]);
        DB::table('transportadoras')->insert(['nome' => trim('SAMID TRANSPORTES LTDA                                 ')]);
        DB::table('transportadoras')->insert(['nome' => trim('SIGMA TRANSPORTES E LOGÍSTICA LTDA. EPP.               ')]);
        DB::table('transportadoras')->insert(['nome' => trim('TRANS AMERICA MAJUCK TRANSPORTES E LOGISTICA LTDA      ')]);
        DB::table('transportadoras')->insert(['nome' => trim('TRANS COFFEE LOGISTICA E TRANSP. LTDA.                 ')]);
        DB::table('transportadoras')->insert(['nome' => trim('TRANSCOOXUPE LTDA                                      ')]);
        DB::table('transportadoras')->insert(['nome' => trim('TRANSLADEIRA TRANSPORTES E LOGISTICA LTDA              ')]);
        DB::table('transportadoras')->insert(['nome' => trim('TRANSPARENCY LOGISTICA E TRANSPORTES                   ')]);
        DB::table('transportadoras')->insert(['nome' => trim('TRANSPORTADORA DOKITO LTDA                             ')]);
        DB::table('transportadoras')->insert(['nome' => trim('TRANSPORTADORA GRANDE CERRADO LTDA.                    ')]);
        DB::table('transportadoras')->insert(['nome' => trim('TRANSQUALITY TRANSPORTES LTDA                          ')]);
        DB::table('transportadoras')->insert(['nome' => trim('TRANSVERDE TRANSPORTES EIRELI                          ')]);
        DB::table('transportadoras')->insert(['nome' => trim('TRANSVERSAL TRANSPORTES E LOGISTICA LTDA               ')]);
        DB::table('transportadoras')->insert(['nome' => trim('VGA LOG SOLUCOES LOGISTICAS                            ')]);
        DB::table('transportadoras')->insert(['nome' => trim('VIA MODAL TRANSPORTES LTDA                             ')]);
        DB::table('transportadoras')->insert(['nome' => trim('VISAO TRANSPORTES                                      ')]);
        DB::table('transportadoras')->insert(['nome' => trim('VISTON TRANSPORTE E LOGISTICA                          ')]);
        DB::table('transportadoras')->insert(['nome' => trim('VPL TAVARES TRANSPORTES LTDA.                          ')]);
        DB::select("select setval('transportadoras_id_seq', (select max(id) from transportadoras))");

        DB::table('modalidades')->insert(['nome' => trim('CONTAINER')]);
        DB::table('modalidades')->insert(['nome' => trim('SACARIA OU ALPHABAG')]);
        DB::table('modalidades')->insert(['nome' => trim('SACARIA COM SACARIA PLÁSTICA')]);
        DB::table('modalidades')->insert(['nome' => trim('GRANEL / BULKLINER')]);
        DB::select("select setval('modalidades_id_seq', (select max(id) from modalidades))");
        
        $seq = 1; //CONTAINER
        DB::table('modalidades_roteiros')->insert(['sequencia' => $seq++, 'descricao' => trim('Frontal do caminhão com a placa do veículo e placa de carregamento'), 'modalidade_id' => 1]);
        DB::table('modalidades_roteiros')->insert(['sequencia' => $seq++, 'descricao' => trim('Lateral esquerda com placa de carregamento'), 'modalidade_id' => 1]);
        DB::table('modalidades_roteiros')->insert(['sequencia' => $seq++, 'descricao' => trim('Lateral direita com placa de carregamento'), 'modalidade_id' => 1]);
        DB::table('modalidades_roteiros')->insert(['sequencia' => $seq++, 'descricao' => trim('Frontal do container com o número do container e placa de carregamento'), 'modalidade_id' => 1]);
        DB::table('modalidades_roteiros')->insert(['sequencia' => $seq++, 'descricao' => trim('trazeira do container fechada com a placa de carregamento'), 'modalidade_id' => 1]);
        DB::table('modalidades_roteiros')->insert(['sequencia' => $seq++, 'descricao' => trim('Trazeira do container aberta sem kit de empapelamento com a placa de carregamento'), 'modalidade_id' => 1]);
        DB::table('modalidades_roteiros')->insert(['sequencia' => $seq++, 'descricao' => trim('Trazeira do container aberta com kit de empapelamento com a placa de carregamento'), 'modalidade_id' => 1]);
        DB::table('modalidades_roteiros')->insert(['sequencia' => $seq++, 'descricao' => trim('Foto dos ganchos'), 'texto' => trim(''), 'modalidade_id' => 1]);
        
        $seq = 1; //SACARIA OU ALPHABAG
        DB::table('modalidades_roteiros')->insert(['sequencia' => $seq++, 'descricao' => trim('Início do carregamento'), 'texto' => trim('Trazeira com porta aberta e primeiras sacas ou primeiro alphabag carregados com placa de carregamento'), 'modalidade_id' => 2]);
        DB::table('modalidades_roteiros')->insert(['sequencia' => $seq++, 'descricao' => trim('Embalagem'), 'texto' => trim('Sacaria com etiqueta / marcação ou alphabag carregados com etiqueta e placa de carregamento'), 'modalidade_id' => 2]);
        DB::table('modalidades_roteiros')->insert(['sequencia' => $seq++, 'descricao' => trim('50% do carregamento'), 'texto' => trim('Trazeira com porta aberta e 50% do container carregado com placa de carregamento'), 'modalidade_id' => 2]);
        DB::table('modalidades_roteiros')->insert(['sequencia' => $seq++, 'descricao' => trim('100% do carregamento'), 'texto' => trim('Trazeira com porta aberta e 100% do container carregado sem fechamento com papel com placa de carregamento'), 'modalidade_id' => 2]);
        DB::table('modalidades_roteiros')->insert(['sequencia' => $seq++, 'descricao' => trim('100% do carregamento e envelopação'), 'texto' => trim('Trazeira com porta aberta e 100% do container carregado envelopado com papel com placa de carregamento'), 'modalidade_id' => 2]);
        DB::table('modalidades_roteiros')->insert(['sequencia' => $seq++, 'descricao' => trim('Carregado com uma das portas fechadas'), 'texto' => trim('Trazeira com a porta contento a numeração do container fechada com placa de carregamento'), 'modalidade_id' => 2]);
        DB::table('modalidades_roteiros')->insert(['sequencia' => $seq++, 'descricao' => trim('Carregado com as portas fechadas'), 'texto' => trim('Trazeira com as portas fechadas com placa de carregamento'), 'modalidade_id' => 2]);
                      

        $seq = 1;
        DB::table('modalidades_roteiros')->insert(['sequencia' => $seq++, 'descricao' => trim('Ensaque'), 'texto' => trim('Sacaria carregada com o(s) lacre(s) da sacaria plástica'), 'modalidade_id' => 3]);
        DB::table('modalidades_roteiros')->insert(['sequencia' => $seq++, 'descricao' => trim('Início do carregamento'), 'texto' => trim('Trazeira com porta aberta e primeiras sacas ou primeiro alphabag carregados com placa de carregamento'), 'modalidade_id' => 3]);
       
        $seq = 1;
        DB::table('modalidades_roteiros')->insert(['sequencia' => $seq++, 'descricao' => trim('Ganchos'), 'texto' => trim('4 fotos dos ganchos com as presilhas'), 'modalidade_id' => 4]);
        DB::table('modalidades_roteiros')->insert(['sequencia' => $seq++, 'descricao' => trim('Início do carregamento'), 'texto' => trim('Trazeira com porta aberta e primeiras sacas ou primeiro alphabag carregados com placa de carregamento'), 'modalidade_id' => 4]);
        DB::select("select setval('modalidades_roteiros_id_seq', (select max(id) from modalidades_roteiros))");

        DB::table('questionarios')->insert(['descricao' => 'CHECK LIST DE CONTAINER', 'texto' => 'CHECK LIST DE CONTAINER']);
        
        $seq = 1;
        DB::table('questionarios_perguntas')->insert(['sequencia' => $seq++, 'pergunta' => 'ODOR (Smell)', 'questionario_id' => 1, 
                                                         'texto' => 'Não possui cheiro forte e fora do comum (Does not have a strong or unusual smell)', 
                                                         'pergunta_imagem' => 'P']);
        DB::table('questionarios_perguntas')->insert(['sequencia' => $seq++, 'pergunta' => 'OFERRUGEM (Rust)', 'questionario_id' => 1, 
                                                         'texto' => 'Não existe ferrugem na parte interna (Rust free interior)', 'pergunta_imagem' => 'P']);        
        DB::table('questionarios_perguntas')->insert(['sequencia' => $seq++, 'pergunta' => 'ASSOALHO (Floor)', 'questionario_id' => 1, 
                                                         'texto' => 'Está limpo e seco (Clean and dry)', 'pergunta_imagem' => 'P']); 
        DB::table('questionarios_perguntas')->insert(['sequencia' => $seq++, 'pergunta' => 'ASSOALHO (Floor)', 'questionario_id' => 1, 
                                                         'texto' => 'Sem pregos e parafusos aparentes (No nails or screws)', 'pergunta_imagem' => 'P']); 
        DB::table('questionarios_perguntas')->insert(['sequencia' => $seq++, 'pergunta' => 'ASSOALHO (Floor)', 'questionario_id' => 1, 
                                                        'texto' => 'Sem marcas de óleo e/ou resíduos químicos (No oil stains or chemical residues)', 'pergunta_imagem' => 'P']); 
        DB::table('questionarios_perguntas')->insert(['sequencia' => $seq++, 'pergunta' => 'PORTAS (Doors)', 'questionario_id' => 1, 
                                                        'texto' => 'Estão em perfeitas condições de fechamento (Perfect close condition)', 'pergunta_imagem' => 'P']); 
        DB::table('questionarios_perguntas')->insert(['sequencia' => $seq++, 'pergunta' => 'PORTAS (Doors)', 'questionario_id' => 1, 
                                                        'texto' => 'As borrachas de vedação estão boas (Perfect rubbers sealing condition)', 'pergunta_imagem' => 'P']);                 
        DB::table('questionarios_perguntas')->insert(['sequencia' => $seq++, 'pergunta' => 'PORTAS (Doors)', 'questionario_id' => 1, 
                                                        'texto' => 'Os parafusos estão arrebitados (Snub screws', 'pergunta_imagem' => 'P']); 
        DB::table('questionarios_perguntas')->insert(['sequencia' => $seq++, 'pergunta' => 'FUROS (Holes)', 'questionario_id' => 1, 
                                                        'texto' => 'Não há furos no teto, parede e assoalho (No holes in the ceiling, wall and floor)', 'pergunta_imagem' => 'P']); 
        DB::table('questionarios_perguntas')->insert(['sequencia' => $seq++, 'pergunta' => 'MARCAS (Symbols)', 'questionario_id' => 1, 
                                                        'texto' => 'Não há símbolo de periculosidade (No warning symbols)', 'pergunta_imagem' => 'P']); 
        DB::table('questionarios_perguntas')->insert(['sequencia' => $seq++, 'pergunta' => 'INTEGRIDADE FÍSICA (Structural integrity)', 'questionario_id' => 1, 
                                                        'texto' => 'Não há compartimentos falsos ou manutenções suspeitas (No hidden compartments or suspicious maintenance)', 'pergunta_imagem' => 'P']); 
        DB::table('questionarios_perguntas')->insert(['sequencia' => $seq++, 'pergunta' => 'INTEGRIDADE FÍSICA (Structural integrity)', 'questionario_id' => 1, 
                                                        'texto' => 'Não há pichações, adesivos ou mensagens suspeitas (No graffiti, stickers or inappropriate messages)', 'pergunta_imagem' => 'P']);   
        DB::table('questionarios_perguntas')->insert(['sequencia' => $seq++, 'pergunta' => 'INTEGRIDADE FÍSICA (Structural integrity)', 'questionario_id' => 1, 
                                                        'texto' => 'Não há sinais de infestação e mofo (No signs of insect infestation or mold)', 'pergunta_imagem' => 'P']); 
        DB::table('questionarios_perguntas')->insert(['sequencia' => $seq++, 'pergunta' => 'INTEGRIDADE FÍSICA (Structural integrity)', 'questionario_id' => 1, 
                                                     'texto' => 'Os respiros estão integros e não estão tampados (The vents are intact and not covered)', 'pergunta_imagem' => 'P']);   
        DB::table('questionarios_perguntas')->insert(['sequencia' => $seq++, 'pergunta' => 'BARRAS DE LACRAÇÃO (Seals Doorknobs)', 'questionario_id' => 1, 
                                                        'texto' => 'As maçanetas possuel furos suficientes para colocação dos lacres (Doorlnobs have enough holes for fixing seals)', 'pergunta_imagem' => 'P']); 
        DB::table('questionarios_perguntas')->insert(['sequencia' => $seq++, 'pergunta' => 'BULKLINER (Bulkliner)', 'questionario_id' => 1, 
                                                        'texto' => 'O carregamento será em bulkliner? (Will the loading be in bulkliner?)', 'pergunta_imagem' => 'P']);   
        DB::table('questionarios_perguntas')->insert(['sequencia' => $seq++, 'pergunta' => 'GANCHOS (Hulks)', 'questionario_id' => 1, 
                                                        'texto' => 'Existem todos os gachos e todos estão íntegros (There are all hulks and they are all intact)', 'pergunta_imagem' => 'P']); 
        DB::table('questionarios_perguntas')->insert(['sequencia' => $seq++, 'pergunta' => 'APROVAÇÃO DO CONTAINER (Container approval)', 'questionario_id' => 1, 
                                                        'texto' => 'O container está aprovado para ser carregado (The container is approved to be loaded)', 'pergunta_imagem' => 'P']);   
        DB::table('questionarios_perguntas')->insert(['sequencia' => $seq++, 'pergunta' => 'REPARO (Repaired)', 'questionario_id' => 1, 
                                                        'texto' => 'Houve algum reparo? Se sim, quais? (Have there been any repairs? If so, which ones?)', 'pergunta_imagem' => 'P']); 

        DB::table('questionarios_perguntas')->insert(['sequencia' => $seq++, 'pergunta' => 'Frontal do caminhão com a placa do veículo e placa de carregamento', 'questionario_id' => 1, 'pergunta_imagem' => 'I']);
        DB::table('questionarios_perguntas')->insert(['sequencia' => $seq++, 'pergunta' => 'Lateral esquerda com placa de carregamento', 'questionario_id' => 1, 'pergunta_imagem' => 'I']);
        DB::table('questionarios_perguntas')->insert(['sequencia' => $seq++, 'pergunta' => 'Lateral direita com placa de carregamento', 'questionario_id' => 1, 'pergunta_imagem' => 'I']);
        DB::table('questionarios_perguntas')->insert(['sequencia' => $seq++, 'pergunta' => 'Frontal do container com o número do container e placa de carregamento', 'questionario_id' => 1, 'pergunta_imagem' => 'I']);
        DB::table('questionarios_perguntas')->insert(['sequencia' => $seq++, 'pergunta' => 'Trazeira do container fechada com a placa de carregamento', 'questionario_id' => 1, 'pergunta_imagem' => 'I']);
        DB::table('questionarios_perguntas')->insert(['sequencia' => $seq++, 'pergunta' => 'Trazeira do container aberta sem kit de empapelamento com a placa de carregamento', 'questionario_id' => 1, 'pergunta_imagem' => 'I']);
        DB::table('questionarios_perguntas')->insert(['sequencia' => $seq++, 'pergunta' => 'Trazeira do container aberta com kit de empapelamento com a placa de carregamento', 'questionario_id' => 1, 'pergunta_imagem' => 'I']);
        DB::table('questionarios_perguntas')->insert(['sequencia' => $seq++, 'pergunta' => 'Foto dos ganchos', 'questionario_id' => 1, 'pergunta_imagem' => 'I']);
        DB::select("select setval('questionarios_perguntas_id_seq', (select max(id) from questionarios_perguntas))");
        
        DB::select("select setval('questionarios_id_seq', 2)");

        DB::table('lacracoes')->insert(['descricao' => 'SEQUÊNCIA DE FOTOS DA LACRAÇÃO DO CONTAINER', 'texto' => 'SEQUÊNCIA DE FOTOS DA LACRAÇÃO DO CONTAINER']);
        DB::select("select setval('lacracoes_id_seq', (select max(id) from lacracoes))");

        DB::table('lacracoes_roteiros')->insert(['descricao' => 'Lacre 01 com numeração', 'lacracao_id' => 1, 'texto' => 'Lacre provisório ou permanente com a placa de carregamento']);
        DB::table('lacracoes_roteiros')->insert(['descricao' => 'Lacre 02 com numeração', 'lacracao_id' => 1, 'texto' => 'Lacre provisório ou permanente com a placa de carregamento']);
        DB::table('lacracoes_roteiros')->insert(['descricao' => 'Traseira do container lacrado', 'lacracao_id' => 1, 'texto' => 'Traseira do container com o(s) lacre(s) e placa de carregamento']);
        DB::select("select setval('lacracoes_roteiros_id_seq', (select max(id) from lacracoes_roteiros))");


    }
}
