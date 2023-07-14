<?php

namespace App\Exports;

use App\Models\Sample;
use App\Models\Project;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;

class SampleExport implements FromQuery, WithHeadings, WithMapping, WithTitle
{
    private $project;

    public function __construct(Project $project)
    {
        $this->project = $project;
    }

    public function title(): string
    {
        return 'Samples';
    }



    /**
     * @return \Illuminate\Support\Query
     */
    public function query()
    {
        return Sample::where('project_id', $this->project->id);
    }

    public function map($sample): array
    {

        $map = [];

        foreach ($this->project->identifiers ?? [] as $identifier) {
            $map[] = $sample->identifiers[$identifier['name']];
        }

        $map[] = $sample->id;
        $map[] = $sample->date;
        $map[] = $sample->depth;
        $map[] = $sample->at_plot;
        $map[] = $sample->longitude;
        $map[] = $sample->latitude;
        $map[] = $sample->altitude;
        $map[] = $sample->accuracy;
        $map[] = $sample->plot_photo;
        $map[] = $sample->comment;
        $map[] = $sample->soil_texture;
        $map[] = $sample->simple_texture;
        $map[] = $sample->ball_yn;
        $map[] = $sample->ribbon_yn;
        $map[] = $sample->ribbon_break_length;
        $map[] = $sample->usda_gritty;
        $map[] = $sample->final_texture_type_usda;
        $map[] = $sample->second_texture_type_usda;
        $map[] = $sample->ball_yn_fao;
        $map[] = $sample->sausage_yn_fao;
        $map[] = $sample->pencil_fao_yn;
        $map[] = $sample->halfcircle_fao_yn;
        $map[] = $sample->soil_circle_choice;
        $map[] = $sample->final_texture_type_fao;
        $map[] = $sample->second_texture_type_fao;

        return $map;
    }


    public function headings(): array
    {
        $headings = [];

        foreach ($this->project->identifiers ?? [] as $identifier) {
            $headings[] = $identifier['label'];
        }


        $headings[] = 'sample_id';
        $headings[] = 'date';
        $headings[] = 'depth';
        $headings[] = 'at_plot';
        $headings[] = 'longitude';
        $headings[] = 'latitude';
        $headings[] = 'altitude';
        $headings[] = 'accuracy';
        $headings[] = 'plot_photo';
        $headings[] = 'comment';
        $headings[] = 'soil_texture';
        $headings[] = 'simple_texture';
        $headings[] = 'ball_yn';
        $headings[] = 'ribbon_yn';
        $headings[] = 'ribbon_break_length';
        $headings[] = 'usda_gritty';
        $headings[] = 'final_texture_type_usda';
        $headings[] = 'second_texture_type_usda';
        $headings[] = 'ball_yn_fao';
        $headings[] = 'sausage_yn_fao';
        $headings[] = 'pencil_fao_yn';
        $headings[] = 'halfcircle_fao_yn';
        $headings[] = 'soil_circle_choice';
        $headings[] = 'final_texture_type_fao';
        $headings[] = 'second_texture_type_fao';
        
        return $headings;
    }
}
