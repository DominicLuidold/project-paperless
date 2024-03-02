import { Route } from "@angular/router";
import { NotFoundComponent } from "./components/not-found/not-found.component";

export const ERROR_ROUTES: Route[] = [
    {
        path: '404',
        component: NotFoundComponent,
    },
];
