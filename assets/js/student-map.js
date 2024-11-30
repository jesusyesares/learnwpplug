document.addEventListener('DOMContentLoaded', function() {
	// Fuction for obtaining the city coordinates
	function getCoordinates(cityName) {
		const url = `https://nominatim.openstreetmap.org/search?format=json&limit=1&q=${encodeURIComponent(cityName)}`;

		return fetch(url, {
			method: "GET",
			headers: {
				"Accept": "application/json",
				"User-Agent": studentMapData.userAgent
			}
		})
		.then(response => response.json())
		.then(data => {
			if (data && data.length > 0) {
				return {
					lat: parseFloat(data[0].lat),
					lon: parseFloat(data[0].lon)
				};
			} else {
				throw new Error("There is no results for the city specified.");
			}
		});
	}   


	// Name of the city
	const cityName = studentMapData.cityName;

	if (cityName) {
		// Now we call to the function to obtain the coordinates
		getCoordinates(cityName)
			.then(coords => {
				// We inizialized the map centered in the obtained coordinates
				const map = L.map("student_map").setView([coords.lat, coords.lon], 13);
	
				// We add the tile layer to the map
				L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
					attribution: "&copy; <a href=\'https://www.openstreetmap.org/copyright\'>OpenStreetMap</a> contributors"
				}).addTo(map);
	
				// We add the marker to the map in the obtained coordinates
				L.marker([coords.lat, coords.lon]).addTo(map)
					.bindPopup(`City: ${cityName}`)
					.openPopup();
				})
				.catch(error => {
					console.error("Error obtaining ubication:", error);
				});
	} else {
		console.error("The city name is not specified.");
	}
});