<?php

namespace App\Enums;

/**
 * Énumération des types de clients
 * 
 * Distingue les clients particuliers des professionnels
 * avec leurs champs spécifiques.
 */
enum CustomerType: string
{
    case PARTICULIER = 'particulier';
    case PROFESSIONNEL = 'professionnel';

    /**
     * Label humain pour l'affichage
     */
    public function label(): string
    {
        return match ($this) {
            self::PARTICULIER => 'Particulier',
            self::PROFESSIONNEL => 'Professionnel',
        };
    }

    /**
     * Label court pour les badges
     */
    public function shortLabel(): string
    {
        return match ($this) {
            self::PARTICULIER => 'Part.',
            self::PROFESSIONNEL => 'Pro',
        };
    }

    /**
     * Classe CSS pour le badge
     */
    public function badgeClass(): string
    {
        return match ($this) {
            self::PARTICULIER => 'secondary',
            self::PROFESSIONNEL => 'primary',
        };
    }

    /**
     * Icône FontAwesome
     */
    public function icon(): string
    {
        return match ($this) {
            self::PARTICULIER => 'fa-user',
            self::PROFESSIONNEL => 'fa-building',
        };
    }

    /**
     * Champs obligatoires pour ce type de client
     * 
     * @return string[]
     */
    public function requiredFields(): array
    {
        return match ($this) {
            self::PARTICULIER => ['username'],
            self::PROFESSIONNEL => ['username', 'company_name', 'siret'],
        };
    }

    /**
     * Champs optionnels spécifiques à ce type
     * 
     * @return string[]
     */
    public function optionalFields(): array
    {
        return match ($this) {
            self::PARTICULIER => ['phone', 'address'],
            self::PROFESSIONNEL => ['tva_number', 'phone', 'address'],
        };
    }

    /**
     * Vérifie si les champs requis sont présents
     */
    public function validateRequired(array $data): array
    {
        $errors = [];
        foreach ($this->requiredFields() as $field) {
            if (empty($data[$field])) {
                $errors[$field] = "Le champ {$field} est obligatoire pour les {$this->label()}s";
            }
        }
        return $errors;
    }

    /**
     * Indique si ce type a des informations d'entreprise
     */
    public function hasCompanyInfo(): bool
    {
        return $this === self::PROFESSIONNEL;
    }

    /**
     * Retourne tous les types
     * 
     * @return CustomerType[]
     */
    public static function all(): array
    {
        return self::cases();
    }

    /**
     * Crée depuis une chaîne avec valeur par défaut
     */
    public static function fromString(string $value): CustomerType
    {
        return self::tryFrom($value) ?? self::PARTICULIER;
    }
}
