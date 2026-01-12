# Pattern State - Gestion des Statuts de Commande

## Vue d'ensemble

Cette implémentation suit les bonnes pratiques du **pattern State** telles que décrites sur [Refactoring Guru](https://refactoring.guru/design-patterns/state).

## Architecture

Le pattern State a été implémenté avec la structure suivante :

```
app/Libraries/OrderState/
├── OrderStateInterface.php          # Interface définissant le contrat pour tous les états
├── AbstractOrderState.php           # Classe abstraite avec comportements communs
├── OrderContext.php                 # Contexte maintenant l'état actuel
├── OrderStateFactory.php            # Factory pour créer les instances d'états
└── States/                          # États concrets
    ├── PayeeState.php               # État "Payée"
    ├── EnPreparationState.php       # État "En préparation"
    ├── PreteState.php               # État "Prête"
    ├── ExpedieeState.php            # État "Expédiée"
    ├── LivreeState.php              # État "Livrée" (terminal)
    └── AnnuleeState.php             # État "Annulée" (terminal)
```

## Composants

### 1. OrderStateInterface

Interface définissant les méthodes que tous les états doivent implémenter :
- `getCode()` : Code du statut
- `getLabel()` : Label d'affichage
- `getBadgeClass()` : Classe CSS pour le badge
- `getIcon()` : Icône FontAwesome
- `getNextPossibleStatuses()` : Transitions possibles
- `canTransitionTo()` : Validation de transition
- `canBeCancelled()` : Possibilité d'annulation
- `isTerminal()` : État terminal ou non
- `onEnter()` : Action à l'entrée de l'état
- `onExit()` : Action à la sortie de l'état
- `transitionTo()` : Gestion de la transition

### 2. AbstractOrderState

Classe abstraite qui :
- Implémente `OrderStateInterface`
- Fournit les comportements par défaut
- Évite la duplication de code
- Peut être étendue par les états concrets

### 3. OrderContext

Le **contexte** qui :
- Maintient une référence à l'état actuel
- Délègue les opérations à l'état actuel
- Gère les transitions entre états
- Stocke les données de la commande
- Expose une API simple pour utiliser le pattern

### 4. OrderStateFactory

La **factory** qui :
- Centralise la création des objets State
- Évite la duplication du code de création
- Fournit des méthodes utilitaires (validation, liste des états, etc.)

### 5. États concrets (States/)

Chaque classe d'état :
- Hérite de `AbstractOrderState`
- Définit ses propres transitions possibles
- Implémente ses comportements spécifiques dans `onEnter()` et `onExit()`
- Peut avoir des règles métier spécifiques

## Diagramme de transition

```
PAYEE
  ├─> EN_PREPARATION
  └─> ANNULEE [terminal]

EN_PREPARATION
  ├─> PRETE
  └─> ANNULEE [terminal]

PRETE
  ├─> EXPEDIEE
  ├─> LIVREE [terminal]
  └─> ANNULEE [terminal]

EXPEDIEE
  └─> LIVREE [terminal]

LIVREE [terminal]
  └─> (aucune transition)

ANNULEE [terminal]
  └─> (aucune transition)
```

## Utilisation

### Exemple 1 : Créer un contexte pour une commande

```php
use App\Libraries\OrderState\OrderContext;

// Créer un contexte avec l'état initial PAYEE
$orderContext = new OrderContext(
    orderId: 123,
    orderData: ['total' => 99.99, 'customer_id' => 456]
);

// Obtenir des informations sur l'état actuel
echo $orderContext->getCurrentStatusLabel();  // "Payée"
echo $orderContext->getCurrentBadgeClass();   // "success"
echo $orderContext->getCurrentIcon();         // "fa-check-circle"
```

### Exemple 2 : Effectuer une transition

```php
// Vérifier les transitions possibles
$possibleStatuses = $orderContext->getNextPossibleStatuses();
// Retourne : ['EN_PREPARATION', 'ANNULEE']

// Effectuer une transition
if ($orderContext->transitionTo('EN_PREPARATION')) {
    echo "Transition réussie !";
    // L'action onEnter() de EnPreparationState a été exécutée
} else {
    echo "Transition invalide !";
}
```

### Exemple 3 : Utiliser avec l'enum OrderStatus

L'enum `OrderStatus` sert de **façade** au pattern State :

```php
use App\Enums\OrderStatus;

$status = OrderStatus::PAYEE;

// L'enum délègue au pattern State
echo $status->label();              // "Payée"
echo $status->badgeClass();         // "success"
echo $status->icon();               // "fa-check-circle"

$nextStatuses = $status->nextPossibleStatuses();
// Retourne un tableau d'enums OrderStatus

if ($status->canBeCancelled()) {
    echo "Cette commande peut être annulée";
}
```

### Exemple 4 : Créer un état depuis un code

```php
use App\Libraries\OrderState\OrderStateFactory;

// Créer un état
$state = OrderStateFactory::createFromCode('PRETE');

echo $state->getLabel();  // "Prête"
echo $state->getCode();   // "PRETE"

// Vérifier les transitions
$nextStatuses = $state->getNextPossibleStatuses();
// Retourne : ['EXPEDIEE', 'LIVREE', 'ANNULEE']
```

### Exemple 5 : Actions personnalisées dans les états

Chaque état peut définir des actions spécifiques :

```php
// Dans PayeeState.php
public function onEnter(OrderContext $context): void
{
    $this->log($context, 'Commande payée et confirmée');
    
    // Envoyer un email de confirmation
    $email = service('email');
    $email->setTo($context->getOrderDataValue('customer_email'));
    $email->setSubject('Commande confirmée');
    $email->send();
    
    // Décrémenter le stock
    $productModel = model('ProductModel');
    foreach ($context->getOrderDataValue('items', []) as $item) {
        $productModel->decrementStock($item['product_id'], $item['quantity']);
    }
}
```

## Avantages de cette implémentation

### ✅ Respect du principe SOLID

- **Single Responsibility** : Chaque état a une seule responsabilité
- **Open/Closed** : Facile d'ajouter de nouveaux états sans modifier le code existant
- **Liskov Substitution** : Tous les états sont interchangeables via l'interface
- **Interface Segregation** : Interface claire et ciblée
- **Dependency Inversion** : Le contexte dépend de l'interface, pas des implémentations

### ✅ Maintenabilité

- Code organisé en fichiers séparés
- Logique de chaque état isolée
- Facile à tester unitairement
- Documentation claire

### ✅ Extensibilité

- Ajout facile de nouveaux états
- Ajout facile de nouveaux comportements
- Pas de modifications massives du code existant

### ✅ Lisibilité

- Intention claire du code
- Transitions explicites
- Pas de conditionnelles complexes (`if`/`switch`)

## Différences avec l'ancienne implémentation

### Avant (un seul fichier Enum)

```php
// Tout dans OrderStatus.php avec des match/switch
public function nextPossibleStatuses(): array
{
    return match ($this) {
        self::PAYEE => [self::EN_PREPARATION, self::ANNULEE],
        self::EN_PREPARATION => [self::PRETE, self::ANNULEE],
        // ...
    };
}
```

**Problèmes** :
- ❌ Tout le code dans un seul fichier
- ❌ Difficile d'ajouter des comportements complexes
- ❌ Pas d'actions à l'entrée/sortie d'un état
- ❌ Logique métier dispersée

### Maintenant (Pattern State complet)

```php
// Chaque état dans son propre fichier
class PayeeState extends AbstractOrderState
{
    public function getNextPossibleStatuses(): array
    {
        return ['EN_PREPARATION', 'ANNULEE'];
    }
    
    public function onEnter(OrderContext $context): void
    {
        // Actions spécifiques à cet état
    }
}
```

**Avantages** :
- ✅ Chaque état dans son propre fichier
- ✅ Actions personnalisées par état
- ✅ Logique métier encapsulée
- ✅ Facile à étendre et maintenir

## Tests

Exemples de tests unitaires :

```php
// Test d'un état concret
public function testPayeeStateTransitions()
{
    $state = new PayeeState();
    
    $this->assertEquals('PAYEE', $state->getCode());
    $this->assertEquals('Payée', $state->getLabel());
    $this->assertTrue($state->canTransitionTo('EN_PREPARATION'));
    $this->assertTrue($state->canTransitionTo('ANNULEE'));
    $this->assertFalse($state->canTransitionTo('LIVREE'));
}

// Test du contexte
public function testOrderContextTransition()
{
    $context = new OrderContext(1, []);
    
    $this->assertEquals('PAYEE', $context->getCurrentStatusCode());
    
    $result = $context->transitionTo('EN_PREPARATION');
    $this->assertTrue($result);
    $this->assertEquals('EN_PREPARATION', $context->getCurrentStatusCode());
}
```

## Références

- [State Pattern - Refactoring Guru](https://refactoring.guru/design-patterns/state)
- [Factory Method Pattern](https://refactoring.guru/design-patterns/factory-method)
- [SOLID Principles](https://en.wikipedia.org/wiki/SOLID)
