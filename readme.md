# Laravel + Livewire Projekt

Dieses Projekt wurde mit Laravel als Backend-Framework und Livewire entwickelt, 
um dynamische Benutzeroberflächen zu erstellen, ohne komplexes JavaScript schreiben zu müssen.

## Einführung

Diese Anwendung nutzt die Leistungsfähigkeit von Laravel in Kombination mit Livewire, um ein reaktives 
und dynamisches Benutzererlebnis zu bieten und gleichzeitig die Einfachheit der serverseitigen PHP-Entwicklung beizubehalten.

## Voraussetzungen

- PHP 8.1 oder höher
- Composer
- Node.js und NPM
- MySQL oder eine andere kompatible Datenbank
-  von Laravel und Livewire. Alpine.js und Tailwind CSS .

## Installation

### 1. Repository klonen

```bash
git clone <REPOSITORY_URL>
cd <PROJEKT_NAME>
```

### 2. PHP-Abhängigkeiten installieren

```bash
composer install
```

### 3. JavaScript-Abhängigkeiten installieren

```bash
npm install
```

### 4. Umgebungsvariablen konfigurieren

Kopieren Sie die Beispiel-Umgebungsdatei und konfigurieren Sie sie mit Ihren Einstellungen:

```bash
cp .env.example .env
```

Bearbeiten Sie die `.env`-Datei, um Ihre Datenbankverbindung, den Anwendungsschlüssel und andere Umgebungsvariablen zu konfigurieren.

### 5. Anwendungsschlüssel generieren

```bash
php artisan key:generate
```

### 6. Datenbank konfigurieren

Führen Sie die Migrationen und Seeder aus:

```bash
php artisan migrate --seed
```

### 7. Assets kompilieren

```bash
npm run dev
```

Für die Produktion:

```bash
npm run build
```

### 8. Entwicklungsserver starten

```bash
php artisan serve
oder composer run dev
```

Die Anwendung ist unter [http://localhost:8000](http://localhost:8000) erreichbar.

### 9. E-Mail-Verifizierung

Benutzer müssen ihre E-Mail-Adresse verifizieren, bevor sie auf bestimmte Funktionen der Anwendung zugreifen können. Nach der Registrierung wird eine Verifizierungs-E-Mail an die angegebene E-Mail-Adresse gesendet. Der Benutzer muss auf den Link in der E-Mail klicken, um seine E-Mail-Adresse zu bestätigen.

## Technologie-Stack

Dieses Projekt verwendet:

- **Laravel**: PHP-Framework für das Backend 
- **Livewire**: Framework zum Erstellen dynamischer Benutzeroberflächen mit PHP
- **Alpine.js**: Leichtgewichtiges JavaScript-Framework zur Verbesserung der Interaktivität (in Livewire integriert)
- **Tailwind CSS**: Utility-First CSS-Framework für das Styling

## Mehr Details 

```bash
php artisan about
```

## Arbeiten mit Livewire

Livewire ermöglicht die Erstellung reaktiver Benutzeroberflächen ohne komplexes JavaScript:

- Livewire-Komponenten befinden sich in `app/Http/Livewire/`
- Die entsprechenden Blade-Ansichten befinden sich in `resources/views/livewire/`
- Komponenten können in normale Blade-Ansichten eingebunden oder als vollständige Seiten verwendet werden

## Funktionen

- Vollständige Benutzerauthentifizierung mit E-Mail-Verifizierung
- Reaktive Benutzeroberfläche mit Echtzeit-Updates
- Systembenachrichtigungen
- Teaser-Verwaltung mit Administrator-Benachrichtigungen
- [Weitere projektspezifische Funktionen]

## Entwicklungstipps

### Testumgebung und Admin-Rolle

Für Testzwecke wird automatisch eine Admin-Rolle zugewiesen. Dies ermöglicht das Testen aller Funktionen und Berechtigungen ohne manuelle Konfiguration. Bitte beachten Sie, dass diese Funktion nur für die Entwicklungs- und Testumgebung gedacht ist und in der Produktionsumgebung deaktiviert werden sollte.

### Erstellen einer Livewire-Komponente

```bash
php artisan make:livewire KomponentenName
```

Dieser Befehl erstellt sowohl die PHP-Klasse als auch die Blade-Ansichtsdatei.

### Komponenten aktualisieren

Um eine Livewire-Komponente nach einer Änderung zu aktualisieren, können Sie Folgendes verwenden:

```php
$this->emit('refresh');
```

### Benachrichtigungen

Systembenachrichtigungen verwenden das Laravel-Benachrichtigungssystem mit Anpassungen für Livewire. Benachrichtigungen können per E-Mail gesendet und in der Benutzeroberfläche angezeigt werden.

## Bereitstellung

Um die Anwendung in der Produktion bereitzustellen:

1. Klonen Sie das Repository auf Ihrem Server
2. Konfigurieren Sie einen Webserver (Nginx, Apache), der auf den Ordner `public/` zeigt
3. Folgen Sie den oben genannten Installationsschritten
4. Für die Produktionsumgebung ändern Sie `.env`:
   ```
   APP_ENV=production
   APP_DEBUG=false
   ```
5. Optimieren Sie die Anwendung:
   ```bash
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```

## Fehlerbehebung

### Livewire-Komponenten werden nicht aktualisiert

Stellen Sie sicher, dass die Livewire-Skripte korrekt in Ihrem Layout eingebunden sind:

```html
@livewireStyles
<!-- Ihr Inhalt -->
@livewireScripts
```

Für Livewire 3 können Sie die Assets veröffentlichen:

```bash
php artisan livewire:publish --assets
```

### Bilder werden nicht angezeigt

Um Bilder korrekt anzuzeigen, müssen Sie einen symbolischen Link zum Storage-Verzeichnis erstellen. Führen Sie den folgenden Befehl aus:

```bash
php artisan storage:link
```

Dieser Befehl erstellt einen symbolischen Link von `public/storage` zu `storage/app/public`, wodurch hochgeladene Dateien im öffentlichen Verzeichnis zugänglich werden.

