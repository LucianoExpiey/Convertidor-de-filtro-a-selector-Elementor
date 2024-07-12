<?php
/*
Plugin Name: Transformador de filtro elementor a selector
Description: Plugin para transformar elementos de búsqueda en un filtro personalizado.
Version: 1.0
Author: Luciano Lyall | EXPIEY.COM
*/

function enqueue_custom_filter_script() {
    // Script para transformar filtros en selectores
    $script = "
    document.addEventListener('DOMContentLoaded', function() {
        var searchElements = document.querySelectorAll('search.e-filter');

        searchElements.forEach(function(searchElement) {
            searchElement.style.display = 'none';
            var buttons = searchElement.querySelectorAll('.e-filter-item');
            var container = searchElement.closest('.elementor-widget-taxonomy-filter');

            if (buttons.length > 0 && container) {
                var select = document.createElement('select');
                select.classList.add('e-filter');
                select.setAttribute('role', 'search');
                select.setAttribute('data-base-url', searchElement.getAttribute('data-base-url'));
                select.setAttribute('data-page-num', searchElement.getAttribute('data-page-num'));
                select.setAttribute('data-page-x', searchElement.getAttribute('data-page-x'));

                buttons.forEach(function(button) {
                    var option = document.createElement('option');
                    option.value = button.getAttribute('data-filter');
                    option.text = button.textContent.trim();
                    if (button.getAttribute('aria-pressed') === 'true') {
                        option.selected = true;
                    }
                    select.appendChild(option);
                });

                container.appendChild(select);

                select.addEventListener('change', function() {
                    var selectedOption = select.value;
                    buttons.forEach(function(button) {
                        if (button.getAttribute('data-filter') === selectedOption) {
                            button.click();
                        }
                    });
                });
            }
        });
    });
    ";
    
    // Script para funcionalidad de búsqueda
    $script .= "
    jQuery(document).ready(function($) {
        $('#search-form').submit(function(event) {
            event.preventDefault();
            var searchTerm = $('#search-term').val();
            $.ajax({
                url: '".admin_url('admin-ajax.php')."',
                type: 'POST',
                data: {
                    action: 'search_filter_process',
                    searchTerm: searchTerm,
                    nonce: '".wp_create_nonce('search-filter-nonce')."'
                },
                success: function(response) {
                    if (response.success) {
                        $('#search-results').html(response.message);
                    } else {
                        // Manejar errores
                    }
                },
                error: function(xhr, status, error) {
                    // Manejar errores AJAX
                }
            });
        });
    });
    ";
    
    wp_add_inline_script('jquery', $script);
}
add_action('wp_enqueue_scripts', 'enqueue_custom_filter_script');

// Procesa la solicitud de búsqueda
function search_filter_process() {
    $response = array(
        'success' => true,
        'message' => 'Resultados de la búsqueda...'
    );

    // Devuelve la respuesta como JSON
    wp_send_json($response);
}
add_action('wp_ajax_search_filter_process', 'search_filter_process');
add_action('wp_ajax_nopriv_search_filter_process', 'search_filter_process');
