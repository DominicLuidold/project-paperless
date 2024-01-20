import { AfterViewInit, Component, ViewChild } from '@angular/core';
import { PaginatedStudyProgrammeResponse, StudyProgrammeResponse } from "../../../models/generated";
import { MatPaginator, MatPaginatorModule } from '@angular/material/paginator';
import { MatSort, MatSortModule } from '@angular/material/sort';
import { MatTableModule } from '@angular/material/table';
import { catchError, merge, Observable, of as observableOf, startWith, switchMap } from "rxjs";
import { map } from "rxjs/operators";
import { StudyProgrammeService } from "../../../shared/services/study-programme.service";
import { MatToolbar } from "@angular/material/toolbar";
import { MatButton, MatIconButton } from "@angular/material/button";
import { MatIcon } from "@angular/material/icon";
import { MatMenu, MatMenuItem, MatMenuTrigger } from "@angular/material/menu";

@Component({
    selector: 'app-study-programme-overview',
    templateUrl: './study-programme-overview.component.html',
    styleUrl: './study-programme-overview.component.scss',
    standalone: true,
    imports: [
        MatButton,
        MatIcon,
        MatIconButton,
        MatMenu,
        MatMenuItem,
        MatMenuTrigger,
        MatPaginatorModule,
        MatSortModule,
        MatTableModule,
        MatToolbar,
    ]
})
export class StudyProgrammeOverviewComponent implements AfterViewInit {
    displayedColumns: string[] = ['name', 'type', 'menu'];
    data: StudyProgrammeResponse[] = [];

    resultsLength = 0;
    isLoadingResults = true;

    @ViewChild(MatPaginator) paginator!: MatPaginator;
    @ViewChild(MatSort) sort!: MatSort;

    constructor(private readonly studyProgrammeService: StudyProgrammeService) {
    }

    ngAfterViewInit(): void {
        this.sort.sortChange.subscribe((): number => (this.paginator.pageIndex = 0));

        merge(this.sort.sortChange, this.paginator.page)
            .pipe(
                startWith({}),
                switchMap((): Observable<PaginatedStudyProgrammeResponse|null> => {
                    this.isLoadingResults = true;

                    return this.studyProgrammeService.getAllStudyProgrammes(
                        this.sort.active,
                        this.sort.direction,
                        this.paginator.pageIndex + 1,
                        this.paginator.pageSize,
                    ).pipe(catchError((): Observable<null> => observableOf(null)));
                }),
                map((data: PaginatedStudyProgrammeResponse|null): StudyProgrammeResponse[] => {
                    this.isLoadingResults = false;
                    this.resultsLength = data?.total ?? 0;

                    return data?._embedded ?? [];
                })
            ).subscribe((data: StudyProgrammeResponse[]) => (this.data = data));
    }
}
