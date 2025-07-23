/**
 * Pegawai Select2 with AJAX Search
 * Handles employee and official selection with searchable dropdown
 */

// Initialize Select2 for employee fields
function initializePegawaiSelect() {
    // Employee field
    $('#namapegawai').select2({
        placeholder: 'Pilih atau cari pegawai...',
        allowClear: true,
        minimumInputLength: 2,
        ajax: {
            url: '/api/pegawai/search',
            dataType: 'json',
            delay: 300,
            data: function (params) {
                return {
                    q: params.term,
                    page: params.page || 1
                };
            },
            processResults: function (data, params) {
                params.page = params.page || 1;
                
                if (data.success) {
                    return {
                        results: data.data.items,
                        pagination: {
                            more: data.data.incomplete_results
                        }
                    };
                } else {
                    return {
                        results: []
                    };
                }
            },
            cache: true
        },
        templateResult: function(item) {
            if (item.loading) {
                return item.text;
            }
            return $('<span>' + item.text + '</span>');
        },
        templateSelection: function(item) {
            return item.text || item.id;
        }
    });
    
    // Official field
    $('#namapejabat').select2({
        placeholder: 'Pilih atau cari pejabat...',
        allowClear: true,
        minimumInputLength: 2,
        ajax: {
            url: '/api/pegawai/search',
            dataType: 'json',
            delay: 300,
            data: function (params) {
                return {
                    q: params.term,
                    page: params.page || 1
                };
            },
            processResults: function (data, params) {
                params.page = params.page || 1;
                
                if (data.success) {
                    return {
                        results: data.data.items,
                        pagination: {
                            more: data.data.incomplete_results
                        }
                    };
                } else {
                    return {
                        results: []
                    };
                }
            },
            cache: true
        },
        templateResult: function(item) {
            if (item.loading) {
                return item.text;
            }
            return $('<span>' + item.text + '</span>');
        },
        templateSelection: function(item) {
            return item.text || item.id;
        }
    });
    
    // Handle employee selection
    $('#namapegawai').on('select2:select', function (e) {
        var data = e.params.data;
        if (data.data) {
            // Auto-fill employee fields
            $('#nippegawai').val(data.data.nip);
            if (data.data.golongan) {
                $('#pangkatgolpegawai').val(data.data.golongan);
            }
            if (data.data.jabatan) {
                $('#jabatanpegawai').val(data.data.jabatan);
            }
            if (data.data.unit_kerja) {
                $('#unitkerja, #tempattugas').val(data.data.unit_kerja);
            }
        }
    });
    
    // Handle official selection
    $('#namapejabat').on('select2:select', function (e) {
        var data = e.params.data;
        if (data.data) {
            // Auto-fill official fields
            $('#nippejabat').val(data.data.nip);
            if (data.data.golongan) {
                $('#pangkatgolpejabat').val(data.data.golongan);
            }
            if (data.data.jabatan) {
                $('#jabatanpejabat').val(data.data.jabatan);
            }
            if (data.data.unit_kerja) {
                $('#ukerpejabat').val(data.data.unit_kerja);
            }
        }
    });
    
    // Handle clear selection
    $('#namapegawai').on('select2:clear', function (e) {
        $('#nippegawai, #pangkatgolpegawai, #jabatanpegawai, #unitkerja, #tempattugas').val('');
    });
    
    $('#namapejabat').on('select2:clear', function (e) {
        $('#nippejabat, #pangkatgolpejabat, #jabatanpejabat, #ukerpejabat').val('');
    });
}

// Initialize when document is ready
(function() {
    function waitForJQuery() {
        if (typeof $ !== 'undefined' && typeof $.fn.select2 !== 'undefined') {
            $(document).ready(function() {
                initializePegawaiSelect();
            });
        } else {
            setTimeout(waitForJQuery, 100);
        }
    }
    waitForJQuery();
})();

// Re-initialize after AJAX content load
function reinitializePegawaiSelect() {
    if (typeof $.fn.select2 !== 'undefined') {
        // Destroy existing Select2 instances
        $('#namapegawai, #namapejabat').select2('destroy');
        // Re-initialize
        initializePegawaiSelect();
    }
}