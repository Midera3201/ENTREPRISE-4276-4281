<?php

if (!function_exists('badgeStatut')) {
    function badgeStatut($statut)
    {
        $classes = [
            'en_attente' => 'warning',
            'approuvee' => 'success',
            'refusee' => 'danger',
            'annulee' => 'secondary',
        ];

        $class = $classes[$statut] ?? 'secondary';
        return "<span class='badge bg-$class'>" . ucfirst($statut) . "</span>";
    }
}

if (!function_exists('badgeTypeConge')) {
    function badgeTypeConge($type)
    {
        $classes = [
            'annuel' => 'primary',
            'maladie' => 'info',
            'special' => 'secondary',
            'sans_solde' => 'dark',
        ];

        $class = $classes[$type] ?? 'secondary';
        return "<span class='badge bg-$class'>" . ucfirst($type) . "</span>";
    }
}

if (!function_exists('afficherFlash')) {
    function afficherFlash()
    {
        $session = session();
        $types = ['success', 'error', 'warning', 'info'];
        $output = '';

        foreach ($types as $type) {
            if ($message = $session->getFlashdata($type)) {
                $output .= "<div class='alert alert-$type alert-dismissible fade show' role='alert'>";
                $output .= htmlspecialchars($message);
                $output .= "<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>";
                $output .= "</div>";
            }
        }

        return $output;
    }
}