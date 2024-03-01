/* generated using openapi-typescript-codegen -- do no edit */
/* istanbul ignore file */
/* tslint:disable */
/* eslint-disable */

import type { StudyProgrammeFilter } from './StudyProgrammeFilter';

export type GetAllStudyProgrammesQuery = {
    filters: StudyProgrammeFilter;
    page: number;
    limit: number;
    sort: 'id' | 'type';
    order: 'asc' | 'desc';
};

