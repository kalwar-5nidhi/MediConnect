document.getElementById('details-btn').addEventListener('click', function() {
    const detailsDiv = document.getElementById('ambulance-details');

    if (!detailsDiv.classList.contains('hidden')) {
        detailsDiv.classList.add('hidden');
        detailsDiv.innerHTML = '';
        return;
    }

    fetch('get_ambulance_details.php')
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            let html = '<ul>';
            if (Array.isArray(data)) {
                data.forEach(item => {
                    html += `
                        <li>
                            <strong>Hospital:</strong> ${item.hospital_name}<br>
                            <strong>Driver:</strong> ${item.driver_name}<br>
                            <strong>Ambulance No:</strong> ${item.ambulance_number}<br>
                            <strong>Contact:</strong> <a href="tel:${item.contact}">${item.contact}</a><br><hr>
                        </li>
                    `;
                });
            } else if (data.message) {
                html += `<p>${data.message}</p>`;
            } else if (data.error) {
                html += `<p class="error">${data.error}</p>`;
            }
            html += '</ul>';
            detailsDiv.innerHTML = html;
            detailsDiv.classList.remove('hidden');
        })
        .catch(error => {
            console.error('Error fetching data:', error);
            detailsDiv.innerHTML = '<p class="error">Error loading data.</p>';
            detailsDiv.classList.remove('hidden');
        });
});