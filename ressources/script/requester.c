#include <stdio.h>
#include <curl/curl.h>

// Fonction de rappel appelée par CURL pour chaque donnée reçue
static size_t write_callback(char *ptr, size_t size, size_t nmemb, void *userdata) {
    printf("%.*s", (int)(size * nmemb), ptr);
    return size * nmemb;
}

int main() {
    CURL *curl = curl_easy_init();
    CURLcode res;

    if(curl) {
        // Configurer la requête CURL
        curl_easy_setopt(curl, CURLOPT_URL, "https://api.meteo-concept.com/api/location/city?token=54a854ee6ec945d4e652e2bab6d258dd482b973d78af92886cfa4241e2d228bc&insee=75056");
        curl_easy_setopt(curl, CURLOPT_FOLLOWLOCATION, 1L);
        curl_easy_setopt(curl, CURLOPT_WRITEFUNCTION, write_callback);

        // Exécuter la requête CURL
        res = curl_easy_perform(curl);
        if(res != CURLE_OK) {
            fprintf(stderr, "curl_easy_perform() failed: %s\n", curl_easy_strerror(res));
        }

        // Nettoyer CURL
        curl_easy_cleanup(curl);
    }
    return 0;
}
