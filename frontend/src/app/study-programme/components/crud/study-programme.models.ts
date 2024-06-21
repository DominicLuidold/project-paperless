import { StudyProgrammeType } from "../../models/study-programme-type.enum";

export enum StudyProgrammeFields {
    Name = 'name',
    Type = 'type',
    NumberOfSemesters = 'numberOfSemesters',
    Code = 'code',
}

export interface StudyProgrammeForm {
    [StudyProgrammeFields.Name]: string,
    [StudyProgrammeFields.Type]: StudyProgrammeType,
    [StudyProgrammeFields.NumberOfSemesters]: number;
    [StudyProgrammeFields.Code]: string;
}
