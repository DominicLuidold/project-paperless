import { Injectable } from "@angular/core";
import { Actions, createEffect, ofType } from "@ngrx/effects";
import { StudyProgrammeBackendService } from "../services/study-programme-backend.service";
import { StudyProgrammeActions } from "./study-programme.actions";
import { catchError, exhaustMap, of } from "rxjs";
import { map } from "rxjs/operators";

@Injectable()
export class StudyProgrammeEffects {
    getAllStudyProgrammesEffect$ = createEffect(
        (): Actions => this.actions$.pipe(
            ofType(StudyProgrammeActions.getAll),
            exhaustMap(({payload}) => this.studyProgrammeBackend.getAllStudyProgrammes(payload)
                .pipe(
                    map(response => StudyProgrammeActions.getAllSuccess({payload: response})),
                    catchError(() => of(StudyProgrammeActions.getAllFailure))
                )
            )
        )
    );

    getStudyProgrammeEffect$ = createEffect(
        (): Actions => this.actions$.pipe(
            ofType(StudyProgrammeActions.get),
            exhaustMap(({payload}) => this.studyProgrammeBackend.getStudyProgramme(payload.id)
                .pipe(
                    map(response => StudyProgrammeActions.getSuccess({payload: response})),
                    catchError(() => of(StudyProgrammeActions.getFailure))
                )
            )
        )
    );

    createStudyProgrammeEffect$ = createEffect(
        (): Actions => this.actions$.pipe(
            ofType(StudyProgrammeActions.create),
            exhaustMap(({payload}) => this.studyProgrammeBackend.createStudyProgramme(payload)
                .pipe(
                    map(response => StudyProgrammeActions.createSuccess({payload: response})),
                    catchError(() => of(StudyProgrammeActions.createFailure()))
                )
            )
        )
    );

    updateStudyProgrammeEffect$ = createEffect(
        (): Actions => this.actions$.pipe(
            ofType(StudyProgrammeActions.update),
            exhaustMap(({payload}) => this.studyProgrammeBackend.updateStudyProgramme(payload.id, payload.studyProgramme)
                .pipe(
                    map(response => StudyProgrammeActions.updateSuccess({payload: response})),
                    catchError(() => of(StudyProgrammeActions.updateFailure()))
                )
            )
        )
    );

    constructor(
        private readonly actions$: Actions,
        private readonly studyProgrammeBackend: StudyProgrammeBackendService,
    ) {
    }
}
