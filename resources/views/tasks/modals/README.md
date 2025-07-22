# Modal Unifié de Gestion des Tâches

## 📁 Structure des fichiers

```
resources/views/tasks/modals/
├── task-form.blade.php    # Modal unifié création/édition
├── scripts.blade.php      # JavaScript pour le modal
└── styles.blade.php       # CSS personnalisé pour le modal
```

## 🎯 Fonctionnalités

### ✨ **Modal Unique**

-   **Création** et **édition** de tâches dans le même composant
-   Détection automatique du mode basée sur les paramètres URL
-   Interface adaptative selon le contexte

### 🔧 **Fonctionnalités Principales**

-   ✅ Formulaire complet avec tous les champs de tâche
-   ✅ Validation en temps réel
-   ✅ Gestion des utilisateurs assignés avec checkboxes
-   ✅ Sélection de catégories et colonnes
-   ✅ Gestion de la priorité et dates d'échéance
-   ✅ Statut de complétion (en édition)
-   ✅ Bouton de suppression intégré (en édition)

### 🎨 **Améliorations UX**

-   ✨ Animations fluides d'ouverture/fermeture
-   ✨ Auto-resize des textareas
-   ✨ Focus automatique sur le titre
-   ✨ Fermeture par Échap ou clic extérieur
-   ✨ Indicateur de chargement lors de la soumission
-   ✨ Validation visuelle des champs requis
-   ✨ Notifications toast pour les confirmations

## 🚀 Utilisation

### **Création d'une tâche**

```php
// URL : /projects/{project}?show_modal=create_task
// Avec colonne spécifique : &column_id={id}

<a href="{{ route('projects.show', $project) }}?show_modal=create_task">
    Nouvelle tâche
</a>

<a href="{{ route('projects.show', $project) }}?show_modal=create_task&column_id=1">
    Nouvelle tâche dans colonne
</a>
```

### **Édition d'une tâche**

```php
// URL : /projects/{project}?show_modal=edit_task&task_id={id}

<a href="{{ route('projects.show', $project) }}?show_modal=edit_task&task_id={{ $task->id }}">
    Modifier la tâche
</a>
```

## 🎛️ Variables disponibles dans le modal

### **Variables du contrôleur**

-   `$project` : Projet courant
-   `$users` : Utilisateurs du projet
-   `$categories` : Catégories du projet
-   `$editTask` : Tâche à éditer (si en mode édition)

### **Variables calculées dans le modal**

-   `$isEdit` : Booléen indiquant le mode édition
-   `$task` : Tâche courante (null en création)
-   `$modalTitle` : Titre dynamique du modal
-   `$submitText` : Texte du bouton de soumission
-   `$formAction` : URL d'action du formulaire

## 🔄 Flux de données

### **Création**

1. **URL** : `?show_modal=create_task`
2. **Action** : `POST /projects/{project}/tasks`
3. **Redirection** : Vers le projet avec message de succès

### **Édition**

1. **URL** : `?show_modal=edit_task&task_id={id}`
2. **Chargement** : Tâche via ProjectController
3. **Action** : `PUT /projects/{project}/tasks/{task}`
4. **Redirection** : Vers le projet avec message de succès

## 🎯 Points clés de l'implémentation

### **Détection du mode**

```php
@php
    $isEdit = request('show_modal') === 'edit_task' && isset($editTask);
@endphp
```

### **Champs conditionnels**

```php
@if($isEdit)
    <!-- Champs spécifiques à l'édition -->
    <div class="bg-gray-50 rounded-md p-3 border border-gray-200">
        <!-- Checkbox de complétion -->
    </div>
@endif
```

### **Valeurs pré-remplies**

```php
value="{{ old('title', $task->title ?? '') }}"
{{ old('is_completed', $task->is_completed ?? false) ? 'checked' : '' }}
```

## 📱 Responsive Design

-   Modal adaptatif pour mobile/desktop
-   Scroll automatique si contenu trop grand
-   Taille optimisée : `max-w-lg` sur desktop, `calc(100vw - 2rem)` sur mobile

## 🔒 Sécurité

-   Vérification des permissions via les policies
-   Protection CSRF intégrée
-   Validation côté serveur maintenue
-   Vérification d'accès au projet

## 🎨 Personnalisation

### **CSS personnalisé disponible dans `styles.blade.php`**

-   Animations d'entrée
-   Styles pour les checkboxes utilisateurs
-   États de focus améliorés
-   Responsive design
-   Thème cohérent avec l'application

### **JavaScript personnalisé dans `scripts.blade.php`**

-   Gestion des événements clavier
-   Auto-resize des textareas
-   Animations des checkboxes
-   Validation en temps réel
-   Protection contre la double soumission
