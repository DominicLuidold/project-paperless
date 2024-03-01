import { ApplicationConfig, isDevMode } from '@angular/core';
import { provideAnimations } from '@angular/platform-browser/animations';
import { provideHttpClient } from "@angular/common/http";
import { provideRouter } from '@angular/router';
import { routes } from './app.routes';
import { provideState, provideStore } from '@ngrx/store';
import { provideStoreDevtools } from '@ngrx/store-devtools';
import { studyProgrammeReducer } from "./study-programme/store/study-programme.reducer";
import { provideEffects } from '@ngrx/effects';
import { StudyProgrammeEffects } from "./study-programme/store/study-programme.effects";

export const appConfig: ApplicationConfig = {
    providers: [
        provideAnimations(),
        provideHttpClient(),
        provideRouter(routes),

        // NgRx
        provideStore(),
        provideStoreDevtools({maxAge: 25, logOnly: !isDevMode()}),
        provideState(studyProgrammeReducer),
        provideEffects(StudyProgrammeEffects)
    ]
};
