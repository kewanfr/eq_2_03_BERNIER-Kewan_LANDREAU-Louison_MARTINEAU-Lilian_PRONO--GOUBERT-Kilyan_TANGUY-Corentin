<?php

namespace App\Libraries\OrderState;

use App\Libraries\OrderState\States\PayeeState;
use App\Libraries\OrderState\States\EnPreparationState;
use App\Libraries\OrderState\States\PreteState;
use App\Libraries\OrderState\States\ExpedieeState;
use App\Libraries\OrderState\States\LivreeState;
use App\Libraries\OrderState\States\AnnuleeState;

/**
 * Factory pour créer les objets State
 * 
 * Cette factory centralise la création des états et évite de dupliquer
 * la logique de création dans tout le code.
 * 
 * @see https://refactoring.guru/design-patterns/factory-method
 */
class OrderStateFactory
{
    /**
     * Crée un état à partir de son code
     * 
     * @param string $statusCode Le code du statut
     * @return OrderStateInterface L'instance de l'état
     * @throws \InvalidArgumentException Si le code de statut est invalide
     */
    public static function createFromCode(string $statusCode): OrderStateInterface
    {
        return match (strtoupper($statusCode)) {
            'PAYEE' => new PayeeState(),
            'EN_PREPARATION' => new EnPreparationState(),
            'PRETE' => new PreteState(),
            'EXPEDIEE' => new ExpedieeState(),
            'LIVREE' => new LivreeState(),
            'ANNULEE' => new AnnuleeState(),
            default => throw new \InvalidArgumentException("Invalid status code: {$statusCode}"),
        };
    }

    /**
     * Retourne tous les codes de statuts disponibles
     * 
     * @return string[]
     */
    public static function getAllStatusCodes(): array
    {
        return [
            'PAYEE',
            'EN_PREPARATION',
            'PRETE',
            'EXPEDIEE',
            'LIVREE',
            'ANNULEE',
        ];
    }

    /**
     * Retourne toutes les instances d'états disponibles
     * 
     * @return OrderStateInterface[]
     */
    public static function getAllStates(): array
    {
        return array_map(
            fn($code) => self::createFromCode($code),
            self::getAllStatusCodes()
        );
    }

    /**
     * Vérifie si un code de statut est valide
     */
    public static function isValidStatusCode(string $statusCode): bool
    {
        return in_array(strtoupper($statusCode), self::getAllStatusCodes(), true);
    }
}
