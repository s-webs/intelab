<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use Illuminate\Database\Eloquent\Model;
use App\Models\Stock;

use MoonShine\Fields\Image;
use MoonShine\Fields\Switcher;
use MoonShine\Fields\Text;
use MoonShine\Fields\Url;
use MoonShine\Resources\ModelResource;
use MoonShine\Decorations\Block;
use MoonShine\Fields\ID;
use MoonShine\Fields\Field;
use MoonShine\Components\MoonShineComponent;

/**
 * @extends ModelResource<Stock>
 */
class StockResource extends ModelResource
{
    protected string $model = Stock::class;

    protected string $title = 'Stocks';

    /**
     * @return list<MoonShineComponent|Field>
     */
    public function fields(): array
    {
        return [
            Block::make([
                ID::make()->sortable(),
                Text::make('Название', 'name')->required(),
                Image::make('Изображение баннера', 'image')
                    ->removable()
                    ->dir('uploads'),
                Url::make('Ссылка', 'link'),
                Switcher::make('Активно', 'is_active')
            ]),
        ];
    }

    /**
     * @param Stock $item
     *
     * @return array<string, string[]|string>
     * @see https://laravel.com/docs/validation#available-validation-rules
     */
    public function rules(Model $item): array
    {
        return [];
    }
}
