<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class CrudGeneratorCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:crud
            {name : The name of the model. ex: ModelName}
            {--table= : The name of the table}
            {--force : Overwrite existing all CRUD files}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Simple CRUD API command';

    /**
     * @var File
     */
    private $file;

    /**
     * Create a new command instance.
     */
    public function __construct(Filesystem $file)
    {
        parent::__construct();

        $this->file = $file;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if (! $this->isPascalCase()) {
            $this->error('The model name is not in PascalCase.');

            return;
        }

        $tableName = $this->tableName();

        if (! Schema::hasTable($tableName)) {
            $this->error("The table {$tableName} does not exist.");

            return;
        }

        $this->info('Generate controller...');
        $this->controller();

        $this->info('Generate model...');
        $this->model();

        $this->info('Generate request...');
        $this->request();

        $this->info('Generate resource...');
        $this->resource();

        $this->info('Generate test...');
        $this->factory();
        $this->test();

        $routeName = $this->routeName();

        $controllerName = $this->argument('name').'Controller::class';

        $this->info('Append route resources...');
        $routeContent = "\nRoute::get('{$routeName}/schema', [\\App\\Http\Controllers\\{$controllerName}, 'schema']);\n";
        $routeContent .= "Route::resource('{$routeName}', \\App\\Http\\Controllers\\{$controllerName});";

        File::append(
            base_path('routes/api.php'),
            $routeContent
        );

        $this->info('CRUD '.$this->argument('name').' successfully created.');
    }

    protected function isPascalCase()
    {
        return preg_match('/^[A-Z][a-zA-Z]*$/', $this->argument('name'));
    }

    protected function getStub($type)
    {
        return file_get_contents(base_path("stubs/crud.{$type}.stub"));
    }

    public function routeName()
    {
        return strtolower($this->plural(Str::kebab($this->argument('name'))));
    }

    protected function plural($name)
    {
        return Str::plural($name);
    }

    /**
     * Create table name
     *
     * @return string
     */
    protected function tableName()
    {
        if ($this->option('table')) {
            return $this->option('table');
        }

        $tableName = Str::plural(Str::snake($this->argument('name')));

        return $tableName;
    }

    public function columnRequired()
    {
        $tableName = $this->tableName();
        $primaryKey = $this->primaryKeyColumn($tableName);
        $columns = $this->tableDetails($tableName);
        $excludedColumns = ['created_at', 'updated_at', 'deleted_at'];

        $requiredColumns = [];
        foreach ($columns as $column) {
            $col = $column->Field;

            if (in_array($col, $excludedColumns) || $col === $primaryKey) {
                continue;
            }

            if ($column->Null === 'YES') {
                continue;
            }

            $requiredColumns[] = $col;
        }

        return $requiredColumns;
    }

    protected function postParams()
    {
        $columns = $this->columnRequired();

        $strRequest = '';
        $strResponse = '';

        if (! empty($columns)) {
            foreach ($columns as $key => $col) {
                $schemaProperty = $this->argument('name').'/properties/'.$col;
                $strRequest .= "*              @OA\Property(property=\"{$col}\", ref=\"#/components/schemas/{$schemaProperty}\"),\n";
                $strResponse .= "*              @OA\Property(property=\"{$col}\", type=\"array\", @OA\Items(example={\"{$col} field is required.\"})),\n";
            }
        }

        $data['request'] = $strRequest;
        $data['response'] = $strResponse;

        return $data;
    }

    protected function controller()
    {
        $tableName = $this->tableName();
        $primaryKey = $this->primaryKeyColumn($tableName);
        $postRequest = $this->postParams();
        $postParam = $postRequest['request'];
        $postResponse = $postRequest['response'];

        $controllerTemplate = str_replace(
            [
                '{{modelName}}',
                '{{modelNamePlural}}',
                '{{modelVariable}}',
                '{{routeName}}',
                '{{tableName}}',
                '{{primaryKey}}',
                '{{postParam}}',
                '{{postResponse}}',
            ],
            [
                $this->argument('name'),
                $this->plural($this->modelVariable($this->argument('name'))),
                $this->modelVariable($this->argument('name')),
                $this->routeName($this->argument('name')),
                $tableName,
                $primaryKey,
                $postParam,
                $postResponse,
            ],
            $this->getStub('controller')
        );

        $filePath = app_path("/Http/Controllers/{$this->argument('name')}Controller.php");

        if ($this->file->exists($filePath) && ! $this->option('force')) {
            if (! $this->confirm('Replace existing controller?')) {
                return;
            }
        }

        file_put_contents($filePath, $controllerTemplate);
    }

    protected function tableDetails($tableName)
    {
        return DB::select('describe '.$tableName);
    }

    protected function columns($tableName)
    {
        return Schema::getColumnListing($tableName);
    }

    protected function modelVariable()
    {
        return Str::camel($this->argument('name'));
    }

    protected function makeFillable()
    {
        $tableName = $this->tableName();
        $columns = $this->tableDetails($tableName);
        $primaryKey = $this->primaryKeyColumn($tableName);
        $excludedColumns = ['created_at', 'updated_at', 'deleted_at'];
        $strFillable = '[';

        foreach ($columns as $column) {
            $col = $column->Field;

            if (in_array($col, $excludedColumns) || $col == $primaryKey) {
                continue;
            }

            $strFillable .= "\n\t\t'{$col}',";
        }

        $strFillable .= "\n\t]";

        return $strFillable;
    }

    protected function makeColumnRules()
    {
        $tableName = $this->tableName();
        $columns = $this->tableDetails($tableName);
        $primaryKey = $this->primaryKeyColumn($tableName);
        $excludedColumns = ['created_by', 'updated_by', 'created_at', 'updated_at', 'deleted_at'];
        $strRules = '[';

        foreach ($columns as $column) {
            $col = $column->Field;

            if (in_array($col, $excludedColumns) || $col === $primaryKey) {
                continue;
            }

            $rule = $column->Null === 'NO' ? 'required' : 'nullable';
            $strRules .= "\n\t\t\t'{$col}' => ['{$rule}'],";
        }

        $strRules .= "\n\t\t]";

        return $strRules;
    }

    /**
     * Mapping data type from Schema to model properties
     *
     * @return array
     */
    public function dataTypes()
    {
        return [
            'integer' => 'int',
            'bigint' => 'int',
            'boolean' => 'bool',
            'string' => 'string',
        ];
    }

    protected function columnType()
    {
        $tableName = $this->tableName();
        $columns = $this->columns($tableName);
        $dataTypes = $this->dataTypes();

        $columnTypes = [];

        foreach ($columns as $k => $v) {
            $columnType = Schema::getColumnType($tableName, $v);
            $columnTypes[$v] = isset($dataTypes[$columnType]) ? $dataTypes[$columnType] : 'string';
        }

        return $columnTypes;
    }

    protected function makeProperties()
    {
        $columnTypes = $this->columnType();
        $properties = '';

        foreach ($columnTypes as $col => $type) {
            $properties .= " * @property {$type} {$col}\n";
        }

        return $properties;
    }

    protected function makeParamProperties()
    {
        $columnTypes = $this->columnType();
        $properties = '';

        foreach ($columnTypes as $col => $type) {
            $properties .= " *      @OA\Property(property=\"{$col}\", type=\"{$type}\"),\n";
        }

        return $properties;
    }

    protected function model()
    {
        $fillable = $this->makeFillable();
        $paramProperties = $this->makeParamProperties();
        $properties = $this->makeProperties();

        $tableProperties = '';
        if ($this->option('table')) {
            $table = $this->option('table');
            $primaryKey = $this->primaryKeyColumn($table);
            $tableProperties = "protected \$table = '{$table}';\n";
            $tableProperties .= "\tprotected \$primaryKey = '{$primaryKey}';";
        }

        $modelTemplate = str_replace(
            ['{{modelName}}', '{{tableProperties}}', '{{fillable}}', '{{paramProperties}}', '{{properties}}'],
            [$this->argument('name'), $tableProperties, $fillable, $paramProperties, $properties],
            $this->getStub('model')
        );

        $filePath = app_path("/Models/{$this->argument('name')}.php");

        if ($this->file->exists($filePath) && ! $this->option('force')) {
            if (! $this->confirm('Replace existing model?')) {
                return;
            }
        }

        file_put_contents($filePath, $modelTemplate);
    }

    protected function primaryKeyColumn($tableName)
    {
        $primaryKey = Schema::getConnection()
            ->getDoctrineSchemaManager()
            ->listTableDetails($tableName)
            ->getPrimaryKey();

        return $primaryKey->getColumns()[0];
    }

    protected function request()
    {
        $columnRules = $this->makeColumnRules();

        $modelTemplate = str_replace(
            ['{{modelName}}', '{{columnRules}}'],
            [$this->argument('name'), $columnRules],
            $this->getStub('request')
        );

        $path = app_path('/Http/Requests');

        $path = $this->makeDirectory($path);

        $filePath = $path.DIRECTORY_SEPARATOR."Store{$this->argument('name')}Request.php";

        if ($this->file->exists($filePath) && ! $this->option('force')) {
            if (! $this->confirm('Replace existing request?')) {
                return;
            }
        }

        file_put_contents($filePath, $modelTemplate);
    }

    protected function resource()
    {
        $keyValues = $this->resourceKeyValue();

        $modelTemplate = str_replace(
            ['{{modelName}}', '{{keyValues}}'],
            [$this->argument('name'), $keyValues],
            $this->getStub('resource')
        );

        $path = app_path('/Http/Resources');

        $path = $this->makeDirectory($path);

        $filePath = $path.DIRECTORY_SEPARATOR."{$this->argument('name')}Resource.php";

        if ($this->file->exists($filePath) && ! $this->option('force')) {
            if (! $this->confirm('Replace existing resource?')) {
                return;
            }
        }

        file_put_contents($filePath, $modelTemplate);
    }

    protected function columnTobeTest()
    {
        $strFillable = "[\n";

        $tableName = $this->tableName();
        $columns = $this->tableDetails($tableName);
        $primaryKey = $this->primaryKeyColumn($tableName);
        $excludedColumns = ['created_at', 'updated_at', 'deleted_at'];

        foreach ($columns as $column) {
            $col = $column->Field;

            if (in_array($col, $excludedColumns) || $col == $primaryKey) {
                continue;
            }

            $columnType = Schema::getColumnType($tableName, $col);
            $columnLength = Schema::getConnection()->getDoctrineColumn($tableName, $col)->getLength();

            switch ($columnType) {
                case 'string':
                    $fakerValue = '$this->faker->text('.$columnLength.')';
                    break;
                case 'integer':
                case 'bigint':
                    $fakerValue = '$this->faker->numberBetween(1, 10)';
                    break;
                case 'boolean':
                    $fakerValue = '$this->faker->randomElement([0,1])';
                    break;
                default:
                    $fakerValue = 'null';
            }

            $strFillable .= "\t\t\t'{$col}' => {$fakerValue},\n";
        }

        $strFillable .= "\t\t]";

        return $strFillable;
    }

    protected function factory()
    {
        $columns = $this->columnTobeTest();

        $modelTemplate = str_replace(
            ['{{modelName}}', '{{columns}}'],
            [$this->argument('name'), $columns],
            $this->getStub('factory')
        );

        $path = base_path('database/factories');

        $filePath = $path.DIRECTORY_SEPARATOR."{$this->argument('name')}Factory.php";

        if ($this->file->exists($filePath) && ! $this->option('force')) {
            if (! $this->confirm('Replace existing factory?')) {
                return;
            }
        }

        file_put_contents($filePath, $modelTemplate);
    }

    protected function test()
    {
        $routeName = $this->routeName();
        $tableName = $this->tableName();

        $modelTemplate = str_replace(
            [
                '{{modelName}}',
                '{{modelVariable}}',
                '{{tableName}}',
                '{{routeName}}',
                '{{primaryKey}}',
                '{{data}}',
            ],
            [
                $this->argument('name'),
                $this->modelVariable(),
                $tableName,
                $routeName,
                $this->primaryKeyColumn($tableName),
                $this->columnTobeTest(),
            ],
            $this->getStub('test')
        );

        $path = base_path('tests/Feature');

        $filePath = $path.DIRECTORY_SEPARATOR."{$this->argument('name')}Test.php";

        file_put_contents($filePath, $modelTemplate);
    }

    protected function resourceKeyValue()
    {
        $tableName = $this->tableName();
        $columns = $this->tableDetails($tableName);
        $strKey = "[\n";

        foreach ($columns as $column) {
            $col = $column->Field;

            $strKey .= "\t\t\t'{$col}' => \$this->{$col},\n";
        }

        $strKey .= "\t\t]";

        return $strKey;
    }

    /**
     * Build the directory for the class if necessary.
     *
     * @param  string  $path
     * @return string
     */
    protected function makeDirectory($path)
    {
        if (! $this->file->isDirectory($path) && ! $this->file->exists($path)) {
            $this->file->makeDirectory($path, 0777, true, true);
        }

        return $path;
    }
}
