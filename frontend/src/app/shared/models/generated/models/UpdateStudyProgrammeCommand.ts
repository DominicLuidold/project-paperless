/* generated using openapi-typescript-codegen -- do no edit */
/* istanbul ignore file */
/* tslint:disable */
/* eslint-disable */

import type { StudyProgrammeId } from './StudyProgrammeId';
import type { StudyProgrammeType } from './StudyProgrammeType';
import type { TranslationValueDto } from './TranslationValueDto';

export type UpdateStudyProgrammeCommand = {
    name: TranslationValueDto;
    type: StudyProgrammeType;
    numberOfSemesters: number;
    code: string;
};

