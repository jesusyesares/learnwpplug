<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

/**
 * Frontend functions.
 */

 /**
 * * Show the student fields in the frontend in each student post.
 * The student fields are retrieved from the student custom post type.
 * TODO: Review the code for fix the problems rendering the map.
 * ! https://chatgpt.com/g/g-674a0939b10481918ae90db180ff77e4-profesor-desarrollo-wordpress/c/674a0c8d-d450-8001-9f09-b1798c672aaf
 */

add_filter('the_content', 'show_student_fields_with_map');
function show_student_fields_with_map($content) {
    if ( is_singular( 'student' ) ) { // Verifies if the current page is a student post.
        // Get the student fields from the student custom post type.
        $student_name = get_post_meta(get_the_ID(), 'name', true);
        $student_surname = get_post_meta(get_the_ID(), 'surname', true);
        $student_address = get_post_meta(get_the_ID(), 'address', true);
        $student_city = get_post_meta(get_the_ID(), 'city', true);
        $student_email = get_post_meta(get_the_ID(), 'email', true);
        // Display the student fields in the frontend.
        $content .= '<p><strong>Name: </strong>' . $student_name . '</p>';
        $content .= '<p><strong>Surname: </strong>' . $student_surname . '</p>';
        $content .= '<p><strong>Address: </strong>' . $student_address . '</p>';
        $content .= '<p><strong>City: </strong>' . esc_html($student_city) . '</p>';
        $content .= '<p><strong>Email: </strong>' . $student_email . '</p>';
        $content .= '<div id="student_map" style="width: 100%; height: 500px; margin-top: 20px;"></div>';

        // Add the student map script to the frontend.
        if ( !empty($student_city) ) {
            // Escape correctly the city name to avoid security issues.
            $city_for_js = json_encode($student_city);
            
            $content .= '<script>
                // Fuction for obtaining the city coordinates
                function getCoordinates(cityName) {
                    const url = `https://nominatim.openstreetmap.org/search?format=json&limit=1&q=${encodeURIComponent(cityName)}`;

                    return fetch(url, {
                        method: "GET",
                        headers: {
                            "Accept": "application/json",
                            "User-Agent": "MiMapa/1.0 (jesus@jesusyesares.com)"
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
                const cityName = ' . $city_for_js . ';

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
            </script>';
        }
    }

    // Return the content with the student fields.
    return $content;
}