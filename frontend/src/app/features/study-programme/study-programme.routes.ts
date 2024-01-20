import { Route } from "@angular/router";
import { StudyProgrammeOverviewComponent } from "./overview/study-programme-overview.component";

export const STUDY_PROGRAMME_ROUTES: Route[] = [
    {
        path: '',
        component: StudyProgrammeOverviewComponent,
    },
];
