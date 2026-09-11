<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SlideResource\Pages;
use App\Models\Slide;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class SlideResource extends Resource
{
    protected static ?string $model = Slide::class;
    protected static ?string $navigationIcon = 'heroicon-o-photo';
    protected static ?int $navigationSort = 7;

    public static function getNavigationLabel(): string { return 'السلايدر'; }
    public static function getModelLabel(): string { return 'شريحة'; }
    public static function getPluralModelLabel(): string { return 'السلايدر'; }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('title_ar')->label('العنوان بالعربية')->maxLength(255),
            Forms\Components\TextInput::make('title_en')->label('العنوان بالإنجليزية')->maxLength(255),
            Forms\Components\FileUpload::make('image_path')
                ->label('الصورة')
                ->disk('public')
                ->directory('slides')
                ->image()
                ->maxSize(4096),
            Forms\Components\FileUpload::make('video_path')
                ->label('الفيديو (قصير)')
                ->disk('public')
                ->directory('slides')
                ->acceptedFileTypes(['video/mp4', 'video/webm', 'video/quicktime'])
                ->maxSize(20480)
                ->helperText('يُعرض بلا صوت — استخدم فيديو قصيرًا'),
            Forms\Components\TextInput::make('link')->label('الرابط عند النقر (اختياري)')->url(),
            Forms\Components\TextInput::make('sort_order')->label('الترتيب')->numeric()->default(0),
            Forms\Components\Toggle::make('is_active')->label('مفعلة')->default(true),
        ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image_path')->label('')->square(),
                Tables\Columns\TextColumn::make('title_ar')->label('العنوان')->limit(30),
                Tables\Columns\TextColumn::make('sort_order')->label('الترتيب')->sortable(),
                Tables\Columns\ToggleColumn::make('is_active')->label('مفعلة'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([])
            ->defaultSort('sort_order');
    }

    public static function getPages(): array
    {
        return ['index' => Pages\ManageSlides::route('/')];
    }
}
