import { Injectable } from "@angular/core";
import { Store } from "@ngrx/store";
import { CreateStudyProgrammeCommand, UpdateStudyProgrammeCommand } from "../../shared/models/generated";
import { StudyProgrammeActions } from "../store/study-programme.actions";

@Injectable({
    providedIn: 'root',
})
export class StudyProgrammeFacade {
    constructor(private readonly store: Store) {
    }

    public createStudyProgramme(studyProgramme: CreateStudyProgrammeCommand): void {
        this.store.dispatch(StudyProgrammeActions.create({ payload: studyProgramme }));
    }

    public updateStudyProgramme(id: string, studyProgramme: UpdateStudyProgrammeCommand): void {
        this.store.dispatch(StudyProgrammeActions.update({ payload: { id, studyProgramme } }));
    }
}
