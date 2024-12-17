<?php

namespace App\Services;

use App\Services\Contracts\Form;

class FilamentFormsServices
{
    public array $forms = [];

    public function register(Form $form){
        $this->forms[] = $form;
    }

    public function getForms(): array
    {
        return $this->forms;
    }

    public function build(): void
    {
        foreach ($this->forms as $form){
            $checkIfFormExists = \App\Models\Form::where('key', $form->key)->first();
            if(!$checkIfFormExists){
                $newForm = \App\Models\Form::create($form->toArray());
                $newForm->fields()->createMany($form->inputs);
            }
        }
    }
}
