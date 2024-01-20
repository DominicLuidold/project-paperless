import { DashboardComponent } from "./features/dashboard/dashboard.component";
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
        loadChildren: () => import('./features/study-programme/study-programme.routes').then(
            mod => mod.STUDY_PROGRAMME_ROUTES
        ),
    },
];
