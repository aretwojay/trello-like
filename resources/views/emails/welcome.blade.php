@component('mail::message')
# Bienvenue {{ $user->name }} ! 🎉

Merci de vous être inscrit sur **{{ $appName }}** ! Nous sommes ravis de vous compter parmi nous.

Votre compte a été créé avec succès et vous pouvez maintenant :

@component('mail::panel')
✅ **Créer des projets** et organiser votre travail  
✅ **Inviter des collaborateurs** à rejoindre vos projets  
✅ **Utiliser notre interface Kanban** pour suivre vos tâches  
✅ **Gérer les échéances** et les priorités  
✅ **Visualiser vos projets** en mode Liste et Calendrier
@endcomponent

## 🚀 Prêt à commencer ?

@component('mail::button', ['url' => $loginUrl, 'color' => 'primary'])
Accéder à mon tableau de bord
@endcomponent

## 💡 Quelques conseils pour bien démarrer :

- **Créez votre premier projet** : Commencez par un projet simple pour vous familiariser
- **Invitez votre équipe** : Ajoutez des collaborateurs pour travailler ensemble
- **Organisez vos tâches** : Utilisez les colonnes pour structurer votre workflow
- **Définissez les priorités** : Marquez vos tâches importantes

---

Si vous avez des questions ou besoin d'aide, n'hésitez pas à nous contacter.

Bonne organisation ! 📋

L'équipe {{ $appName }}

@component('mail::subcopy')
Si vous avez des difficultés à cliquer sur le bouton "Accéder à mon tableau de bord", copiez et collez l'URL suivante dans votre navigateur web : [{{ $loginUrl }}]({{ $loginUrl }})
@endcomponent
@endcomponent
