<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class UserResource extends Resource
{
    protected static ?string $model = User::class;
    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?int $navigationSort = 9;

    public static function getNavigationLabel(): string { return 'العملاء والمستخدمون'; }
    public static function getModelLabel(): string { return 'مستخدم'; }
    public static function getPluralModelLabel(): string { return 'العملاء والمستخدمون'; }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('name')->label('الاسم')->required()->maxLength(100),
            Forms\Components\TextInput::make('email')->label('البريد الإلكتروني')->email()->required()->unique(ignoreRecord: true)->maxLength(150),
            Forms\Components\TextInput::make('phone')->label('الهاتف')->tel()->maxLength(30),
            Forms\Components\Select::make('role')
                ->label('الدور')
                ->options(['customer' => 'عميل', 'admin' => 'مدير'])
                ->default('customer')
                ->required(),
            Forms\Components\Select::make('status')
                ->label('الحالة')
                ->options(['active' => 'نشط', 'blocked' => 'محظور'])
                ->default('active')
                ->required(),
            Forms\Components\TextInput::make('password')
                ->label('كلمة المرور')
                ->password()
                ->revealable()
                ->dehydrated(fn ($state) => filled($state))
                ->required(fn (string $context) => $context === 'create')
                ->minLength(6),
        ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->label('الاسم')->searchable()->weight('bold'),
                Tables\Columns\TextColumn::make('email')->label('البريد')->searchable(),
                Tables\Columns\TextColumn::make('phone')->label('الهاتف'),
                Tables\Columns\TextColumn::make('role')
                    ->label('الدور')
                    ->badge()
                    ->formatStateUsing(fn ($state) => $state === 'admin' ? 'مدير' : 'عميل')
                    ->color(fn ($state) => $state === 'admin' ? 'warning' : 'gray'),
                Tables\Columns\TextColumn::make('orders_count')->label('الطلبات')->counts('orders'),
                Tables\Columns\TextColumn::make('status')
                    ->label('الحالة')
                    ->badge()
                    ->formatStateUsing(fn ($state) => $state === 'blocked' ? 'محظور' : 'نشط')
                    ->color(fn ($state) => $state === 'blocked' ? 'danger' : 'success'),
                Tables\Columns\TextColumn::make('created_at')->label('التسجيل')->date()->sortable(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return ['index' => Pages\ManageUsers::route('/')];
    }
}
