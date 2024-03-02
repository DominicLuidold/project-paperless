import { DashboardComponent } from "./dashboard/dashboard.component";
import { Route } from '@angular/router';

export const routes: Route[] = [
    // Public routes
    // {},
    // Private routes
    {
        path: '',
        redirectTo: '/dashboard',
        pathMatch: 'full',
    },
    {
        path: 'dashboard',
        component: DashboardComponent,
    },
    {
        path: 'study-programmes',
        loadChildren: () => import('./study-programme/study-programme.routes')
            .then(routes => routes.STUDY_PROGRAMME_ROUTES),
    },
    {
        path: 'errors',
        loadChildren: () => import('./error/error.routes')
            .then(routes => routes.ERROR_ROUTES),
    },
    {
        path: '**',
        redirectTo: '/errors/404',
    },
];
