import { createActionGroup, emptyProps, props } from "@ngrx/store";
import {
    CreateStudyProgrammeCommand,
    GetAllStudyProgrammesQuery,
    GetStudyProgrammeQuery,
    PaginatedStudyProgrammeResponse,
    StudyProgrammeResponse,
    UpdateStudyProgrammeCommand
} from "../../shared/models/generated";

export const StudyProgrammeActions = createActionGroup({
    source: 'StudyProgramme',
    events: {
        // Get All
        'Get All': props<{ payload: GetAllStudyProgrammesQuery }>(),
        'Get All Success': props<{ payload: PaginatedStudyProgrammeResponse }>(),
        'Get All Failure': emptyProps(),

        // Get
        'Get': props<{ payload: GetStudyProgrammeQuery }>(),
        'Get Success': props<{ payload: StudyProgrammeResponse }>(),
        'Get Failure': emptyProps(),

        // Create
        'Create': props<{ payload: CreateStudyProgrammeCommand }>(),
        'Create Success': props<{ payload: StudyProgrammeResponse }>(),
        'Create Failure': emptyProps(),

        // Update
        'Update': props<{ payload: { id: string; studyProgramme: UpdateStudyProgrammeCommand } }>(),
        'Update Success': props<{ payload: StudyProgrammeResponse }>(),
        'Update Failure': emptyProps(),
    }
});
