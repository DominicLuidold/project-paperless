import { Identifiable } from "../../shared/models/identifiable.model";
import { StudyProgrammeType } from "./study-programme-type.enum";

export interface StudyProgramme extends Identifiable {
    name: string;
    type: StudyProgrammeType;
    numberOfSemesters: number;
    code: string;
}
