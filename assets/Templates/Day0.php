{{php}}


use App\Advent\Year_2022\Days;

class Day{{day}}
{
    private DataService $dataService;

    public function __construct()
    {
        $this->dataService = new DataService();
    }

    public function runA()
    {
        return $this->run('day{{day}}_test.txt', 'Year_{{year}}/');
    }

    public function runB()
    {
    }

    public function run($file, $state = false) {
        $handle = $this->dataService->read($file);
        while ($handle->valid()) {
            $handle->current();

            $handle->next();
        }

        return "Only a bad programmer.";
    }
}