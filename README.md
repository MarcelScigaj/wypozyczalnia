# wypozyczalnia


1. Przygotowanie Repozytorium GitHub:
Umieszczono kod aplikacji CRUD w repozytorium GitHub.
Dodano pliki Dockerfile i Dockerfile-php dla konteneryzacji aplikacji PHP i MongoDB.
2. Konfiguracja Dockerfile:
Utworzono Dockerfile dla MongoDB i Dockerfile-php dla aplikacji PHP.
Skonfigurowano Docker Compose (docker-compose.yml) do zarządzania wielokontenerowym środowiskiem.
3. Konfiguracja GitHub Actions (deploy.yml):
Utworzono plik konfiguracyjny YAML w katalogu .github/workflows.
Skonfigurowano GitHub Actions do budowania i publikacji obrazu Docker do Azure Container Registry (ACR).
4. Konfiguracja Azure Container Registry (ACR):
Utworzono Azure Container Registry w portalu Azure.
Zainstalowano Azure CLI i zalogowano się do Azure.
Skonfigurowano serwer ACR jako sekret w ustawieniach repozytorium GitHub.
5. Konfiguracja Usługi Azure Container Instances (ACI):
Utworzono plik aci.yml, definiujący konfigurację usługi Azure Container Instances.
Dostosowano plik aci.yml do potrzeb projektu, w tym konfiguracji kontenera i portów.
6. Dodanie Kroku Deploy w GitHub Actions:
Dodano krok do wdrożenia aplikacji na Azure w pliku deploy.yml, używając az container create.
7. Ustawienia Sekretów w GitHub:
Ustawiono sekrety w ustawieniach repozytorium GitHub, w tym REGISTRY_NAME, REGISTRY_USERNAME, REGISTRY_PASSWORD, RESOURCE_GROUP i AZURE_CREDENTIALS.
8. Testowanie i Wdrażanie:
Wprowadzono zmiany do kodu i wypchnięto je na główną gałąź repozytorium GitHub.
GitHub Actions automatycznie uruchomił się, zbudował obraz Docker, opublikował go w Azure Container Registry, a następnie wdrożył aplikację w Azure Container Instances.
9. Monitorowanie:
Skonfigurowano odpowiednie narzędzia monitorowania i logowania w Azure, aby utrzymać bieżący stan aplikacji.
Rozwiązywanie Problemów:
Napotkano i rozwiązano różne problemy, w tym konflikty w plikach, błędy połączenia z bazą danych i kwestie związane z konfiguracją GitHub Actions.
Ten proces stanowi kompleksowe podejście do wdrożenia aplikacji webowej z wykorzystaniem Docker, GitHub Actions i Azure, zapewniając ciągłą integrację i wdrażanie (CI/CD).
 
