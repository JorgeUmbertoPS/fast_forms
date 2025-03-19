<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class Questionario extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.questionario';

    protected static ?string $slug = 'questionario/{id}';

    protected static bool $shouldRegisterNavigation = false;
}
