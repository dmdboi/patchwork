<?php

namespace App\Livewire;

use App\Services\FilamentCMSFormBuilder;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Livewire\Component;

class FormPreview extends Component implements HasForms
{
    use InteractsWithForms;

    public $form_id;

    public $component = 'form-preview';

    public $data = [];

    public function mount($form_id)
    {
        $this->form_id = $form_id;
    }

    public function form(Form $form)
    {
        return $form->schema(FilamentCMSFormBuilder::make($this->form_id)->build() ?? [])->statePath('data');
    }

    public function submit()
    {
        FilamentCMSFormBuilder::make($this->form_id)->send($this->form->getState());

        redirect()->to('/admin/forms/'.$this->form_id.'/edit');
    }

    public function render()
    {
        return view('livewire.'.$this->component);
    }
}
