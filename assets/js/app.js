// Live Search - AJAX tanpa refresh
(function() {
    'use strict';

    var searchInput = document.getElementById('liveSearch');
    if (!searchInput) return;

    var type = searchInput.getAttribute('data-type');
    var tableBody = document.getElementById('tableBody');
    var debounceTimer = null;
    var currentPage = 1;

    // Determine base URL for ajax
    var baseUrl = '../ajax/';

    // Debounce: tunggu 300ms setelah user berhenti mengetik
    searchInput.addEventListener('keyup', function() {
        clearTimeout(debounceTimer);
        currentPage = 1;
        debounceTimer = setTimeout(function() {
            fetchData();
        }, 300);
    });

    function fetchData() {
        var query = searchInput.value.trim();
        var url = baseUrl + 'search' + capitalize(type) + '.php?q=' + encodeURIComponent(query) + '&page=' + currentPage;

        var xhr = new XMLHttpRequest();
        xhr.open('GET', url, true);
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
                try {
                    var response = JSON.parse(xhr.responseText);
                    renderTable(response.data, response.page, response.totalPages, response.total);
                    renderPagination(response.page, response.totalPages);
                } catch (e) {
                    console.error('Error parsing response:', e);
                }
            }
        };
        xhr.send();
    }

    function renderTable(data, page, totalPages, total) {
        var limit = 10;
        var startNo = (page - 1) * limit + 1;
        var html = '';

        if (data.length === 0) {
            var colspan = type === 'tagihan' ? 8 : 7;
            html = '<tr><td colspan="' + colspan + '" class="text-center">Data tidak ditemukan</td></tr>';
        } else {
            for (var i = 0; i < data.length; i++) {
                var row = data[i];
                html += '<tr>';

                if (type === 'tagihan') {
                    html += renderTagihanRow(row, startNo + i);
                } else if (type === 'pelanggan') {
                    html += renderPelangganRow(row, startNo + i);
                } else if (type === 'karyawan') {
                    html += renderKaryawanRow(row, startNo + i);
                }

                html += '</tr>';
            }
        }

        tableBody.innerHTML = html;
    }

    function renderTagihanRow(row, no) {
        var statusBadge = row.statusBayar === 'Lunas'
            ? '<span class="badge badge-success">Lunas</span>'
            : '<span class="badge badge-danger">Belum Bayar</span>';

        return '<td>' + no + '</td>' +
            '<td>' + escapeHtml(row.noRekening) + '</td>' +
            '<td>' + escapeHtml(row.namaPelanggan) + '</td>' +
            '<td>' + row.periodeBulan + '/' + row.periodeTahun + '</td>' +
            '<td>' + row.pemakaian + ' m³</td>' +
            '<td>Rp ' + formatNumber(row.totalTagihan) + '</td>' +
            '<td>' + statusBadge + '</td>' +
            '<td>' +
                '<a href="editTagihan.php?id=' + row.id + '" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></a> ' +
                '<a href="hapusTagihan.php?id=' + row.id + '" class="btn btn-danger btn-sm" onclick="return confirm(\'Yakin hapus data ini?\')"><i class="fas fa-trash"></i></a>' +
            '</td>';
    }

    function renderPelangganRow(row, no) {
        return '<td>' + no + '</td>' +
            '<td>' + escapeHtml(row.noRekening) + '</td>' +
            '<td>' + escapeHtml(row.namaPelanggan) + '</td>' +
            '<td>' + escapeHtml(row.alamat) + '</td>' +
            '<td>' + escapeHtml(row.noHp) + '</td>' +
            '<td>' + escapeHtml(row.namaKategori || '-') + '</td>' +
            '<td>' +
                '<a href="editPelanggan.php?id=' + row.id + '" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></a> ' +
                '<a href="hapusPelanggan.php?id=' + row.id + '" class="btn btn-danger btn-sm" onclick="return confirm(\'Yakin hapus data ini?\')"><i class="fas fa-trash"></i></a>' +
            '</td>';
    }

    function renderKaryawanRow(row, no) {
        return '<td>' + no + '</td>' +
            '<td>' + escapeHtml(row.nik) + '</td>' +
            '<td>' + escapeHtml(row.namaKaryawan) + '</td>' +
            '<td>' + escapeHtml(row.jabatan) + '</td>' +
            '<td>' + escapeHtml(row.noHp) + '</td>' +
            '<td>' + escapeHtml(row.alamat) + '</td>' +
            '<td>' +
                '<a href="editKaryawan.php?id=' + row.id + '" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></a> ' +
                '<a href="hapusKaryawan.php?id=' + row.id + '" class="btn btn-danger btn-sm" onclick="return confirm(\'Yakin hapus data ini?\')"><i class="fas fa-trash"></i></a>' +
            '</td>';
    }

    function renderPagination(page, totalPages) {
        var wrapper = document.getElementById('paginationWrapper');
        var pagination = document.getElementById('pagination');

        // Jika belum ada wrapper, buat
        if (!wrapper) {
            var cardFooter = document.createElement('div');
            cardFooter.className = 'card-footer clearfix';
            cardFooter.id = 'paginationWrapper';
            cardFooter.innerHTML = '<ul class="pagination pagination-sm m-0 float-right" id="pagination"></ul>';
            tableBody.closest('.card').appendChild(cardFooter);
            wrapper = cardFooter;
            pagination = cardFooter.querySelector('#pagination');
        }

        if (totalPages <= 1) {
            wrapper.style.display = 'none';
            return;
        }

        wrapper.style.display = '';
        var html = '';

        // Previous
        html += '<li class="page-item ' + (page <= 1 ? 'disabled' : '') + '">';
        html += '<a class="page-link" href="#" data-page="' + (page - 1) + '">&laquo;</a></li>';

        // Pages
        for (var i = 1; i <= totalPages; i++) {
            html += '<li class="page-item ' + (i === page ? 'active' : '') + '">';
            html += '<a class="page-link" href="#" data-page="' + i + '">' + i + '</a></li>';
        }

        // Next
        html += '<li class="page-item ' + (page >= totalPages ? 'disabled' : '') + '">';
        html += '<a class="page-link" href="#" data-page="' + (page + 1) + '">&raquo;</a></li>';

        pagination.innerHTML = html;

        // Bind pagination clicks
        pagination.querySelectorAll('a[data-page]').forEach(function(link) {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                var targetPage = parseInt(this.getAttribute('data-page'));
                if (targetPage >= 1 && targetPage <= totalPages) {
                    currentPage = targetPage;
                    fetchData();
                }
            });
        });
    }

    // Helper functions
    function capitalize(str) {
        return str.charAt(0).toUpperCase() + str.slice(1);
    }

    function escapeHtml(text) {
        if (!text) return '';
        var div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    function formatNumber(num) {
        return parseInt(num).toLocaleString('id-ID');
    }

})();
