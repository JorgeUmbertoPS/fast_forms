<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class ModeloAdmin extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.modelo-admin';

    protected static ?string $slug = 'modelo-admin/{id}';

    protected static bool $shouldRegisterNavigation = false;
}
