/* generated using openapi-typescript-codegen -- do no edit */
/* istanbul ignore file */
/* tslint:disable */
/* eslint-disable */

import type { StudyProgrammeId } from './StudyProgrammeId';
import type { StudyProgrammeType } from './StudyProgrammeType';
import type { TranslationValueObject } from './TranslationValueObject';

export type StudyProgrammeResponse = {
    id: StudyProgrammeId;
    name: TranslationValueObject;
    type: StudyProgrammeType;
    numberOfSemesters: number;
    code: string;
};

