import { Component, OnInit } from '@angular/core';
import { MatDialogActions, MatDialogClose, MatDialogContent, MatDialogTitle } from "@angular/material/dialog";
import { MatFormFieldModule } from "@angular/material/form-field";
import { MatInputModule } from "@angular/material/input";
import { MatSelectModule } from "@angular/material/select";
import { MatButtonModule } from "@angular/material/button";
import { CreateStudyProgrammeCommand, UpdateStudyProgrammeCommand } from "../../../shared/models/generated";
import { FormControl, FormGroup, ReactiveFormsModule, Validators } from "@angular/forms";
import { StudyProgrammeFields, StudyProgrammeForm } from "./study-programme.models";
import { StudyProgrammeFacade } from "../../services/study-programme-facade.service";

@Component({
    selector: 'app-study-programme-crud',
    standalone: true,
    imports: [
        MatButtonModule,
        MatDialogActions,
        MatDialogClose,
        MatDialogContent,
        MatDialogTitle,
        MatFormFieldModule,
        MatInputModule,
        MatSelectModule,
        ReactiveFormsModule
    ],
    templateUrl: './study-programme-crud.component.html',
    styleUrl: './study-programme-crud.component.scss'
})
export class StudyProgrammeCrudComponent implements OnInit {
    public formGroup!: FormGroup;
    public studyProgrammeFields = StudyProgrammeFields;

    constructor(
        private readonly studyProgrammeFacade: StudyProgrammeFacade
    ) {
    }

    public get formValue(): StudyProgrammeForm {
        return this.formGroup.getRawValue();
    }

    ngOnInit(): void {
        this.initialiseForm();
    }

    private initialiseForm() {
        this.formGroup = new FormGroup(
            {
                [StudyProgrammeFields.Name]: new FormControl('', [
                    Validators.required,
                    Validators.maxLength(255),
                ]),
                [StudyProgrammeFields.Type]: new FormControl('', [Validators.required]),
                [StudyProgrammeFields.NumberOfSemesters]: new FormControl('', [Validators.required]),
                [StudyProgrammeFields.Code]: new FormControl('', [
                    Validators.required,
                    Validators.maxLength(255),
                ])
            }
        );
    }

    public onSubmit(): void {
        if (this.formGroup.valid) {
            this.formGroup.markAsPristine();

            // TODO
            const isNew = true;

            if (isNew) {
                const formValue: CreateStudyProgrammeCommand = this.createResource(this.formValue);
                this.create(formValue);
            } else {
                const updateResource: UpdateStudyProgrammeCommand = this.updateResource(this.formValue);
                this.update('uuid', updateResource);
            }
        } else {
            this.formGroup.markAllAsTouched();
        }
    }

    private createResource(form: StudyProgrammeForm): CreateStudyProgrammeCommand {
        return {
            name: {de: form[StudyProgrammeFields.Name]}, // TODO - Handle multi-language support
            type: form[StudyProgrammeFields.Type],
            numberOfSemesters: form[StudyProgrammeFields.NumberOfSemesters],
            code: form[StudyProgrammeFields.Code]
        };
    }

    private updateResource(form: StudyProgrammeForm): UpdateStudyProgrammeCommand {
        return {
            name: {de: form[StudyProgrammeFields.Name]}, // TODO - Handle multi-language support
            type: form[StudyProgrammeFields.Type],
            numberOfSemesters: form[StudyProgrammeFields.NumberOfSemesters],
            code: form[StudyProgrammeFields.Code]
        };
    }

    private create(data: CreateStudyProgrammeCommand): void {
        this.studyProgrammeFacade.createStudyProgramme(data);
    }

    private update(id: string, data: UpdateStudyProgrammeCommand): void {
        this.studyProgrammeFacade.updateStudyProgramme(id, data);
    }
}
