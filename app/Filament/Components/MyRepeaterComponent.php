<?php

namespace App\Filament\Components;

use Filament\Support\Enums\ActionSize;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Actions\Action;

class MyRepeaterComponent extends Repeater
{
    public function getCollapseAllAction(): Action
    {
        $action = Action::make($this->getCollapseAllActionName())
            ->label(__('filament-forms::components.repeater.actions.collapse_all.label'))
            ->icon('heroicon-m-chevron-up')
            ->color('gray')
            ->livewireClickHandlerEnabled(false)
            ->link()
            ->size(ActionSize::Small);

        if ($this->modifyCollapseAllActionUsing) {
            $action = $this->evaluate($this->modifyCollapseAllActionUsing, [
                'action' => $action,
            ]) ?? $action;
        }

        return $action;
    }

    public function getExpandAllAction(): Action
    {
        $action = Action::make($this->getExpandAllActionName())
            ->label(__('filament-forms::components.repeater.actions.expand_all.label'))
            ->icon('heroicon-m-chevron-down')
            ->color('gray')
            ->livewireClickHandlerEnabled(false)
            ->link()
            ->size(ActionSize::Small);

        if ($this->modifyExpandAllActionUsing) {
            $action = $this->evaluate($this->modifyExpandAllActionUsing, [
                'action' => $action,
            ]) ?? $action;
        }

        return $action;
    }

    public function getExpandAllActionName(): string
    {
        return 'expandAll';
    }

      

}