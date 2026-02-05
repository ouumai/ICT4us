<?php
if (!function_exists('servis_icon_url')) {
    function servis_icon_url(array $servis): string
    {
        if (!empty($servis['imejkad']) && !empty($servis['icon_path'])) {
            return base_url(trim($servis['icon_path'], '/') . '/' . $servis['imejkad']);
        }
        return base_url('assets/icons/default.png');
    }
}
