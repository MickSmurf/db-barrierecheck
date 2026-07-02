<script setup></script>

<template>
<div id="app-container">
   <header>
    <h1>DB Barrierefreiheits-Check</h1>
    <p>Finde heraus, welche Bahnhöfe wirklich stufenlos zugänglich sind.</p>
  </header>
  <section class="search-section">
    <div class="search-box">
        <input v-model="searchQuery" @keyup.enter="searchStations" type="text" placeholder="z.b. Bielefeld ...." />
        <button @click="searchStations" :disabled="loading">
            {{ loading ? 'Suche...' : 'Suchen' }}
        </button>
    </div>
  </section>

  <div v-if="Loading" class="status loading">Lade Bahnhofsdaten...</div>
  <div v-if="error" class="status error">{{ error }}</div>

  <main v-if="stations.length > 0 && !loading">
    <div class="stats">
        {{ stations.length }} Bahnhof/Bahnhöfe gefunden
    </div>

    <div class="stations-grid">
        <div v-for="station in stations" :key="station.number" class="station-card" :class="{'is-accessible' : station.hasSteplessAccess === 'yes'}">
            <div class="card-header">
                <h3>{{ station.name }}</h3>
                <span class="Bundesland">{{ station.federalState }}</span>
            </div>
            <div class="card-body">
                <div class="info-row">
                    <span class="label">Stufenloser Zugang:</span>
                    <span class="badge" :class="station.hasSteplessAccess === 'yes' ? 'success' : 'danger'">
                        {{ station.hasSteplessAccess === 'yes' ? 'Ja' : 'Nein / Unbekannt'}}
                    </span>
                </div>
                <div class="info-row">
                    <span class="label">Öffentliche Einrichtungen:</span>
                    <span class="badge" :class="station.hasPublicFacilities ? 'success' : 'neutral'">
                        {{ station.hasPublicFacilities ? 'Vorhanden' : 'Keine' }}
                    </span>
                </div>
            </div>
        </div>
    </div>

  </main>

  <div v-if="stations.length === 0 && !loading && hasSearched" class="no-results">
    Keine Bahnhöfe für "{{ searchQuery }}" gefunden.
  </div>
 </div>
</template>

<script>
    export default {
        data() {
            return {
                searchQuery: '',
                stations: [],
                loading: false,
                error: null,
                hasSearched: false
            }
        }, // Diese Klammer schließt NUR data()
        methods: {
            async searchStations() {
                if (!this.searchQuery.trim()) return;

                this.loading = true;
                this.error = null;
                this.stations = [];
                this.hasSearched = true;

                try {

                    const response = await fetch(`/api/stations.php?search=${encodeURIComponent(this.searchQuery)}`);

                    if (!response.ok) {
                        throw new Error(`Server-Fehler: ${response.status}`);
                    }

                    const data = await response.json();

                    if (data.stations) {
                        this.stations = data.stations;
                    } else {
                        this.stations = [];
                    }
                } catch (err) {
                    this.error = "Fehler beim Laden der Daten: " + err.message;
                } finally {
                    this.loading = false;
                }
            }
        }
    }
</script>

<style scoped>
#app-container {
  font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
  color: #212529;
  background-color: #f8f9fa;
  min-height: 100vh;
  padding: 2rem 1rem;
  max-width: 1200px;
  margin: 0 auto;
  box-sizing: border-box;
}

/* --- Header --- */
header {
  text-align: center;
  margin-bottom: 3rem;
}

header h1 {
  font-size: 2.5rem;
  color: #ec0016; /* DB Rot */
  margin-bottom: 0.5rem;
  font-weight: 800;
}

header p {
  color: #6c757d;
  font-size: 1.1rem;
}

/* --- Suchbereich --- */
.search-section {
  display: flex;
  justify-content: center;
  margin-bottom: 3rem;
}

.search-box {
  display: flex;
  width: 100%;
  max-width: 600px;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
  border-radius: 8px;
  overflow: hidden;
}

.search-box input {
  flex: 1;
  padding: 1rem 1.5rem;
  border: 2px solid #fff;
  font-size: 1.1rem;
  outline: none;
  transition: border-color 0.2s;
}

.search-box input:focus {
  border-color: #ec0016;
}

.search-box button {
  background-color: #ec0016;
  color: white;
  border: none;
  padding: 0 2rem;
  font-size: 1.1rem;
  font-weight: 600;
  cursor: pointer;
  transition: background-color 0.2s;
}

.search-box button:hover:not(:disabled) {
  background-color: #c50012;
}

.search-box button:disabled {
  background-color: #ccc;
  cursor: not-allowed;
}

/* --- Status- & Info-Meldungen --- */
.status {
  text-align: center;
  padding: 1rem;
  border-radius: 6px;
  margin-bottom: 2rem;
  font-weight: 500;
}

.status.loading {
  background-color: #e2f0fe;
  color: #0066cc;
}

.status.error {
  background-color: #fde8e8;
  color: #e02424;
}

.stats {
  font-weight: 600;
  color: #4b5563;
  margin-bottom: 1rem;
  font-size: 0.95rem;
}

.no-results {
  text-align: center;
  padding: 3rem;
  background: white;
  border-radius: 8px;
  color: #6b7280;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.02);
}

/* --- Das Stations-Grid --- */
.stations-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
  gap: 1.5rem;
}

/* --- Station Card (Standard: Nicht barrierefrei) --- */
.station-card {
  background: white;
  border-radius: 8px;
  padding: 1.5rem;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.04);
  border-left: 5px solid #d1d5db; /* Grauer Rand standardmäßig */
  transition: transform 0.2s, box-shadow 0.2s;
}

.station-card:hover {
  transform: translateY(-2px);
  box-shadow: 0 6px 12px rgba(0, 0, 0, 0.08);
}

/* Visuelles Highlight: Barrierefreier Bahnhof */
.station-card.is-accessible {
  border-left-color: #2ecc71; /* Grüner Rand */
  background-color: #f7fdf9; /* Ganz leicht grüner Hintergrund */
}

/* --- Card Details --- */
.card-header {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  margin-bottom: 1.5rem;
  gap: 1rem;
}

.card-header h3 {
  margin: 0;
  font-size: 1.25rem;
  font-weight: 700;
  color: #111827;
}

.bundesland {
  font-size: 0.75rem;
  background-color: #f3f4f6;
  color: #4b5563;
  padding: 0.25rem 0.5rem;
  border-radius: 4px;
  white-space: nowrap;
  font-weight: 500;
}

.info-row {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 0.75rem 0;
  border-bottom: 1px solid #f3f4f6;
}

.info-row:last-child {
  border-bottom: none;
}

.label {
  font-size: 0.9rem;
  color: #4b5563;
}

/* --- Badges --- */
.badge {
  font-size: 0.85rem;
  font-weight: 600;
  padding: 0.35rem 0.75rem;
  border-radius: 20px;
}

.badge.success {
  background-color: #def7ec;
  color: #03543f;
}

.badge.danger {
  background-color: #fde8e8;
  color: #9b1c1c;
}

.badge.neutral {
  background-color: #e5e7eb;
  color: #374151;
}

/* Responsive Anpassung für sehr kleine Bildschirme */
@media (max-width: 480px) {
  header h1 {
    font-size: 1.8rem;
  }

  .search-box {
    flex-direction: column;
    box-shadow: none;
    gap: 0.5rem;
  }

  .search-box input {
    border: 1px solid #d1d5db;
    border-radius: 6px;
  }

  .search-box button {
    padding: 0.75rem;
    border-radius: 6px;
  }
}
</style>
