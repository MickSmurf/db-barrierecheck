# DB Barrierefreiheits-Check

Ein interaktives, leichtgewichtiges Tool zur Echtzeit-Abfrage der Barrierefreiheitsdaten von deutschen Bahnhöfen über die offizielle API der Deutschen Bahn.

🚀 **Live-Demo:** [db-check.mikeberg.de](https://db-check.mikeberg.de)

---

## Features
* **Echtzeit-Suche:** Live-Abfrage von Bahnhofsdaten direkt über die DB-Schnittstelle.
* **Intelligentes UI-Mapping:** Dynamische Badges für stufenlose Zugänge, öffentliche Einrichtungen und Mobilitätsservices.
* **Robustes Parsing:** Fängt unstrukturierte API-Antworten (wie Freitext-Telefonnummern bei Voranmeldungen) sauber ab und bereitet sie visuell auf.
* **Sicheres API-Gateway:** Keine API-Keys im Frontend. Alle Anfragen laufen über ein gekapseltes PHP-Backend.

## Tech-Stack
* **Frontend:** Vue.js 3, Vite, CSS3 (Flexbox/Grid)
* **Backend:** PHP 8.x (cURL für API-Anfragen)

## Installation & Setup

1. **Repository klonen:**
   ```bash
   git clone [https://github.com/MickSmurf/db-barrierecheck.git](https://github.com/MickSmurf/db-barrierecheck.git)
   cd db-barrierecheck

2. **Frontend Abhängigkeiten installieren:**
    npm install

3. **API-Keys einrichten**
   Erstelle eine config.php im api/ -Verzeichnis (wird per .gitignore lokal ignoriert)
   Siehe config.example.php

4. **Entwicklungsserver starten**
   npm run dev
   und einen PHP Server für die api
