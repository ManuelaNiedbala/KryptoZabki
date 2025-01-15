import requests

# URL API
api_url = "https://plan.zut.edu.pl/schedule_student.php?room=WI+WI1-+215&start=2024-09-30T00%3A00%3A00%2B02%3A00&end=2024-10-07T00%3A00%3A00%2B02%3A00&fbclid=IwY2xjawH1FYZleHRuA2FlbQIxMAABHT2dH8h76Rta5vXSwqHgIjWKlVC-uCLEvB4mwvk1wfTy-SiiJuU2Mz8gxQ_aem_YcJ0rvJN60U8fT9sNpx3Pg"


def fetch_schedule(url):
    try:
        # Wykonanie zapytania GET
        response = requests.get(url)
        # Sprawdzenie czy zapytanie się powiodło
        response.raise_for_status()

        # Konwersja odpowiedzi do formatu JSON
        data = response.json()
        return data

    except requests.exceptions.RequestException as e:
        print(f"Error fetching data: {e}")
        return None


def display_schedule(data):
    if not data:
        print("No data to display.")
        return

    print("Schedule data:")
    for entry in data:
        # Upewniamy się, że entry to słownik
        if isinstance(entry, dict):
            start = entry.get('start', 'No start time')
            end = entry.get('end', 'No end time')
            title = entry.get('title', 'No title')
            print(f"Start: {start}, End: {end}, Title: {title}")
        else:
            print("Unexpected data format:", entry)


# Główna część programu
if __name__ == "__main__":
    schedule_data = fetch_schedule(api_url)
    display_schedule(schedule_data)
