<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CategoryResource\Pages;
use App\Models\Category;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;
    protected static ?string $navigationIcon = 'heroicon-o-square-3-stack-3d';
    protected static ?int $navigationSort = 3;

    public static function getNavigationLabel(): string { return 'الأقسام'; }
    public static function getModelLabel(): string { return 'قسم'; }
    public static function getPluralModelLabel(): string { return 'الأقسام'; }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('name_ar')->label('الاسم بالعربية')->required()->maxLength(255),
            Forms\Components\TextInput::make('name_en')->label('الاسم بالإنجليزية')->required()->maxLength(255),
            Forms\Components\Select::make('parent_id')
                ->label('القسم الأب (اختياري)')
                ->options(fn () => Category::whereNull('parent_id')->pluck('name_ar', 'id'))
                ->searchable(),
            Forms\Components\FileUpload::make('image')
                ->label('الصورة')
                ->disk('public')
                ->directory('categories')
                ->image()
                ->maxSize(4096),
            Forms\Components\TextInput::make('sort_order')->label('الترتيب')->numeric()->default(0),
            Forms\Components\Toggle::make('is_active')->label('مفعل')->default(true),
        ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image')->label('')->square(),
                Tables\Columns\TextColumn::make('name_ar')->label('الاسم')->searchable(),
                Tables\Columns\TextColumn::make('parent.name_ar')->label('القسم الأب')->placeholder('—'),
                Tables\Columns\TextColumn::make('products_count')->label('المنتجات')->counts('products'),
                Tables\Columns\TextColumn::make('sort_order')->label('الترتيب')->sortable(),
                Tables\Columns\ToggleColumn::make('is_active')->label('مفعل'),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([])
            ->defaultSort('sort_order');
    }

    public static function getPages(): array
    {
        return ['index' => Pages\ManageCategories::route('/')];
    }
}
