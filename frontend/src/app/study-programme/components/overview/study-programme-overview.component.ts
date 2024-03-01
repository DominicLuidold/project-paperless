import { AfterViewInit, Component, ViewChild } from '@angular/core';
import { StudyProgrammeResponse } from "../../../shared/models/generated";
import { MatPaginator, MatPaginatorModule } from '@angular/material/paginator';
import { MatSort, MatSortModule } from '@angular/material/sort';
import { StudyProgrammeBackendService } from "../../services/study-programme-backend.service";
import { MatIconModule } from "@angular/material/icon";
import { MatTableModule } from "@angular/material/table";
import { MatMenuModule } from "@angular/material/menu";
import { MatButtonModule } from "@angular/material/button";
import { TitleCasePipe } from "@angular/common";
import { MatTooltipModule } from "@angular/material/tooltip";
import { MatDividerModule } from "@angular/material/divider";
import { MatDialog } from "@angular/material/dialog";
import { StudyProgrammeCrudComponent } from "../crud/study-programme-crud.component";

@Component({
    selector: 'app-study-programme-overview',
    templateUrl: './study-programme-overview.component.html',
    styleUrl: './study-programme-overview.component.scss',
    standalone: true,
    imports: [
        MatButtonModule,
        MatDividerModule,
        MatIconModule,
        MatMenuModule,
        MatPaginatorModule,
        MatSortModule,
        MatTableModule,
        MatTooltipModule,
        TitleCasePipe,
    ]
})
export class StudyProgrammeOverviewComponent implements AfterViewInit {
    displayedColumns: string[] = ['name', 'type', 'numberOfSemesters', 'code', 'menu'];
    data: StudyProgrammeResponse[] = [];
    resultsLength = 0;

    @ViewChild(MatPaginator) paginator!: MatPaginator;
    @ViewChild(MatSort) sort!: MatSort;

    constructor(
        private readonly studyProgrammeService: StudyProgrammeBackendService,
        private readonly dialog: MatDialog
    ) {
    }

    // /** https://material.angular.io/components/table/examples#table-http */
    // ngAfterViewInit(): void {
    //     this.sort.sortChange.subscribe((): number => (this.paginator.pageIndex = 0));
    //
    //     merge(this.sort.sortChange, this.paginator.page)
    //         .pipe(
    //             startWith({}),
    //             switchMap((): Observable<PaginatedStudyProgrammeResponse | null> => {
    //                 return this.studyProgrammeService.getAllStudyProgrammes(
    //                     this.sort.active,
    //                     this.sort.direction,
    //                     this.paginator.pageIndex + 1,
    //                     this.paginator.pageSize,
    //                 ).pipe(catchError((): Observable<null> => observableOf(null)));
    //             }),
    //             map((data: PaginatedStudyProgrammeResponse | null): StudyProgrammeResponse[] => {
    //                 this.resultsLength = data?.total ?? 0;
    //
    //                 return data?._embedded ?? [];
    //             })
    //         ).subscribe((data: StudyProgrammeResponse[]) => (this.data = data));
    // }

    createStudyProgramme(): void {
        this.dialog.open(StudyProgrammeCrudComponent);
    }

    updateStudyProgramme(studyProgramme: StudyProgrammeResponse): void {
        this.dialog.open(StudyProgrammeCrudComponent, {data: studyProgramme});
    }

    ngAfterViewInit(): void {
    }
}
