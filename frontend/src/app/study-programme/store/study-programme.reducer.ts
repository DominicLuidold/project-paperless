import { createFeature, createReducer, on } from "@ngrx/store";
import { PaginatedStudyProgrammeResponse, StudyProgrammeResponse } from "../../shared/models/generated";
import { StudyProgrammeActions } from "./study-programme.actions";
import { Nullable, StateSlice } from "../../shared/models/interfaces/state-slice.model";

export type StudyProgrammeState = StateSlice<
    Nullable<StudyProgrammeResponse>|
    PaginatedStudyProgrammeResponse
>;

export const initialState: StudyProgrammeState = {
    data: null,
    loading: false,
    loaded: false,
    error: false,
}

export const studyProgrammeReducer = createFeature({
    name: 'studyProgramme',
    reducer: createReducer(
        initialState,
        // Get All
        on(
            StudyProgrammeActions.getAll,
            (_state, _action): StudyProgrammeState => ({
                ...initialState,
                loading: true,
            })
        ),
        on(
            StudyProgrammeActions.getAllSuccess,
            (state, action): StudyProgrammeState => ({
                ...state,
                data: action.payload,
                loading: false,
                loaded: true,
            }),
        ),
        on(
            StudyProgrammeActions.getAllFailure,
            (state): StudyProgrammeState => ({
                ...state,
                loading: false,
                loaded: false,
                error: true,
            }),
        ),

        // Get
        on(
            StudyProgrammeActions.get,
            (_state, _action): StudyProgrammeState => ({
                ...initialState,
                loading: true,
            })
        ),
        on(
            StudyProgrammeActions.getSuccess,
            (state, action): StudyProgrammeState => ({
                ...state,
                data: action.payload,
                loading: false,
                loaded: true,
            }),
        ),
        on(
            StudyProgrammeActions.getFailure,
            (state): StudyProgrammeState => ({
                ...state,
                loading: false,
                loaded: false,
                error: true,
            })
        ),

        // Create
        on(
            StudyProgrammeActions.create,
            (_state, _action): StudyProgrammeState => ({
                ...initialState,
                loading: true,
            })
        ),
        on(
            StudyProgrammeActions.createSuccess,
            (state, action): StudyProgrammeState => ({
                ...state,
                data: action.payload,
                loading: false,
                loaded: true,
            }),
        ),
        on(
            StudyProgrammeActions.createFailure,
            (state): StudyProgrammeState => ({
                ...state,
                loading: false,
                loaded: false,
                error: true,
            }),
        ),

        // Update
        on(
            StudyProgrammeActions.update,
            (_state, _action): StudyProgrammeState => ({
                ...initialState,
                loading: true,
            })
        ),
        on(
            StudyProgrammeActions.updateSuccess,
            (state, action): StudyProgrammeState => ({
                ...state,
                data: action.payload,
                loading: false,
                loaded: true,
            }),
        ),
        on(
            StudyProgrammeActions.updateFailure,
            (state): StudyProgrammeState => ({
                ...state,
                loading: false,
                loaded: false,
                error: true,
            }),
        ),
    ),
});

export const {
    name,
    reducer,
    selectStudyProgrammesState,
    selectData,
    selectLoading,
    selectLoaded,
    selectError,
} = studyProgrammeReducer;
