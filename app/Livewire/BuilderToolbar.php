<?php

namespace App\Livewire;

use App\Facades\FilamentCMS;
use App\Models\Post;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms;
use Filament\Forms\Components\Builder;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Livewire\Component;

class BuilderToolbar extends Component implements HasActions, HasForms
{
    use InteractsWithActions;
    use InteractsWithForms;

    public ?array $data = [];

    public ?Post $page = null;

    public ?bool $loaded = false;

    public ?bool $preview = false;

    public function mount(): void
    {
        $this->form->fill([
            'sections' => $this->page->meta('sections') ?? [],
        ]);

        // Load the page, if it's not already loaded
        if (session()->has('preview_'.$this->page->id)) {
            $this->preview = session()->get('preview_'.$this->page->id);
        } else {
            session()->put('preview_'.$this->page->id, $this->preview);
        }

        // If the user is not logged in, show the preview
        if (! auth()->user()) {
            $this->preview = true;
        }
    }

    public function editPageAction()
    {
        $page = $this->page;

        return Action::make('editPage')
            ->label('Back to Admin')
            ->icon('heroicon-o-arrow-left')
            ->color('info')
            ->action(function (array $data) use ($page) {
                if ($page->type == 'post') {
                    return redirect()->to('/admin/posts/'.$page->id.'/edit');
                }

                return redirect()->to('/admin/pages/'.$page->id.'/edit');
            });
    }

    public function savePageAction()
    {
        $page = $this->page;

        return Action::make('savePage')
            ->label('Save Page')
            ->icon('heroicon-o-lifebuoy')
            ->color('success')
            ->action(function (array $data) use ($page) {
                $this->saveSections();
                $page->save();
            });
    }

    public function previewPageAction()
    {
        $page = $this->page;

        return Action::make('previewPage')
            ->label($this->preview ? 'Show Builder' : 'Preview Page')
            ->icon($this->preview ? 'heroicon-o-eye' : 'heroicon-o-pencil')
            ->color('info')
            ->action(function () use ($page) {
                $this->preview = ! $this->preview;
                session()->put('preview_'.$page->id, $this->preview);
            });
    }

    public function form(Form $form): Form
    {
        $sections = FilamentCMS::themes()->getSections();
        $blocks = [];

        // Add the sections to the form
        foreach ($sections as $section) {
            $blocks[] = Builder\Block::make($section->key)
                ->schema($section->form);
        }

        // Add the form to the form
        return $form
            ->schema([
                Forms\Components\Builder::make('sections')
                    ->blocks($blocks)
                    ->collapsible()
                    ->cloneable()
                    ->required()
                    ->minItems(1)
                    ->columns(2),
            ])->statePath('data');
    }

    public function saveSections(): void
    {
        $sections = collect($this->form->getState()['sections'])->map(function ($item, $key) {
            $item['uuid'] = Str::uuid()->toString();
            $item['order'] = $key;

            return $item;
        });
        $this->page->meta('sections', $sections);

        $this->preview = ! $this->preview;
        session()->put('preview_'.$this->page->id, $this->preview);

        Notification::make()
            ->title('Section added')
            ->body('Section added successfully')
            ->success()
            ->send();
    }

    public function render(): View
    {
        return view('admin.builder-toolbar-livewire');
    }
}
