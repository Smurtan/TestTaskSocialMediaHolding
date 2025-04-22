<?php

namespace App\Console\Commands;

use App\Models\BaseDummyModel;
use App\Services\DummyService;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Console\Command;

class ImportIphoneProducts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:data {model} {--search=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Импортирует данные из Demmy API и сохраняет их в БД';

    protected DummyService $dummyService;

    public function __construct(DummyService $dummyService)
    {
        parent::__construct();
        $this->dummyService = $dummyService;
    }

    /**
     * Execute the console command.
     * @throws \Exception
     */
    public function handle()
    {
        $modelClassName = $this->argument('model');
        $modelClass = "App\\Models\\" . $modelClassName;
        $search = $this->option('search') ?? "";

        $model = new $modelClass();

        $this->info('Импорт данных из класса ' . $modelClassName);

        if (!class_exists($modelClass) || !is_subclass_of($modelClass, BaseDummyModel::class)) {
            throw new \Exception('Класс ' . $modelClass . ' должен наследоваться от BaseDummyModel');
        }

        try {
            $total = 1;
            $get = 0;
            $step = 0;
            while ($get < $total) {
                $query = "?";
                if (!empty($search)) {
                    $query = '/search?q='.urlencode($search);
                }

                $query .= mb_strlen($query) > 1 ? "&limit=30" : "limit=30";
                $query .= '&skip=' . $step * 30;

                $dataResponse = $this->dummyService->getEntities($model, $query);

                $this->info('Импортированные данные: ' . json_encode($dataResponse));

                $total = $dataResponse['total'];
                $getElements = $dataResponse['limit'];
                $get += $getElements;
                $step++;

                if (is_array($dataResponse) && isset($dataResponse[$model->getNameEntity()])) {
                    $data = collect($dataResponse[$model->getNameEntity()]);

                    $data->each(function ($item) use ($model) {
                        $modelData = $model::mapApiData($item);
                        $model::query()->create($modelData);
                    });
                } else {
                    $this->info("Получены неверные данные, пожалуйста, проверьте контракт API.");
                    return;
                }
            }

            $this->info("Данные успешно импортированы.");
        } catch (\Exception $exception) {
            $this->error($exception->getMessage());
        } catch (GuzzleException $e) {
            $this->error($e->getMessage());
        }
    }

}
