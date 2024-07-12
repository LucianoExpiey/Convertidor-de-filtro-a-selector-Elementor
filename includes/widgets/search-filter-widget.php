<?php
namespace Custom_Filter\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

class Search_Filter_Widget extends Widget_Base {

    public function get_name() {
        return 'search-filter-widget';
    }

    public function get_title() {
        return __('Search Filter', 'plugin-name');
    }

    public function get_icon() {
        return 'eicon-search';
    }

    public function get_categories() {
        return ['basic'];
    }

    protected function _register_controls() {
        $this->start_controls_section(
            'section_content',
            [
                'label' => __('Content', 'plugin-name'),
            ]
        );

        $this->add_control(
            'placeholder',
            [
                'label' => __('Placeholder', 'plugin-name'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Enter your search term...', 'plugin-name'),
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();

        // Renderiza el formulario de búsqueda
        ?>
        <form id="elementor-search-form" role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
            <input type="search" class="elementor-search-field" placeholder="<?php echo esc_attr( $settings['placeholder'] ); ?>" value="<?php echo get_search_query(); ?>" name="s" />
            <button type="submit" class="elementor-search-button"><i class="fa fa-search"></i></button>
        </form>
        <?php
    }
}

\Elementor\Plugin::instance()->widgets_manager->register_widget_type(new Search_Filter_Widget());
