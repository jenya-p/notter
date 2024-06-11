<?php

namespace App\Console\Commands;

use App\Models\Quiz\Block;
use GuzzleHttp\Client;
use GuzzleHttp\Cookie\CookieJar;
use Heriw\LaravelSimpleHtmlDomParser\HtmlDomParser;
use Illuminate\Console\Command;
use simplehtmldom\simple_html_dom_node;

class ImportNotatestCommand extends Command {

    protected $signature = 'app:import-notatest';


    // baza003@mail.ru
    // baza003%Z7


    public function handle() {

        $cookieJar = CookieJar::fromArray([
            'MoodleSession' => '7188a57a1643fd17ffeec18cd100409d'
        ], 'notatest.ru');
        $this->client = new Client([
            'base_uri' => 'https://notatest.ru',
            'headers' => [
                'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.7',
                'Accept-Encoding' => 'gzip, deflate, br, zstd',
                'Accept-Language' => 'ru-RU,ru;q=0.9,en-US;q=0.8,en;q=0.7',
                'Cache-Control' => 'max-age=0',
                'Priority' => 'u=0, i',
                'Referer' => 'https://notatest.ru',
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/125.0.0.0 Safari/537.36',
            ],
            'cookies' => $cookieJar
        ]);


        $pairs = $this->getTickets();
        foreach ($pairs as $ticket => $id) {

            $attemptId = $this->getAttemptId($id);

            $this->line('Билет ' . $ticket . ' id=' . $id . ' attempt=' . $attemptId);

            $this->getTicket($attemptId, $ticket, $id);

        }
    }


    public function autoClick($id, $attemptId) {

        sleep(1);
//             Кол-во вопросов
        $html = $this->getRemote('/mod/quiz/attempt.php?attempt=' . $attemptId . '&cmid=' . $id . '#', 'question-' . $attemptId . '-' . 0);
        $questionCount = count($html->find('.qnbutton'));
        for ($i = 0; $i < $questionCount; $i++) {
            $this->line('Вопрос ' . $i);
            if ($i != 0) {
                sleep(1);
                $html = $this->getRemote('/mod/quiz/attempt.php?attempt=' . $attemptId . '&cmid=' . $id . '&page=' . $i, 'question-' . $attemptId . '-' . $i);
            }

            /** @var simple_html_dom_node $hForm */
            $hForm = $html->find('#responseform', 0);
            $data = [];
            $hInputs = $hForm->find('input[type=hidden]');
            foreach ($hInputs as $hInput) {
                if (!empty($hInput->name)) {
                    $data[$hInput->name] = $hInput->value;
                }
            }

            $hInputs = $hForm->find('input[type=radio]');
            if(count($hInputs) == 0){
                $hInputs = $hForm->find('input[type=checkbox]');
                if(count($hInputs) == 0){
                    echo 'Ошибка notatest (6) ' . "\n\n\n";
                    die;
                }
            }
            $hInput = $hInputs[rand(0, count($hInputs) - 1)];
            $data[$hInput->name] = $hInput->value;
            $data['attempt'] = $attemptId;
            $action = $hForm->action;
            $action = substr($action, 19);
            sleep(1);
            $response = $this->client->post($action, [
                'form_params' => $data, 'allow_redirects' => false
            ]);

            if ($response->getStatusCode() != 303) {
                echo 'Ошибка notatest (2) ' . $response->getStatusCode() . "\n\n\n";
                echo $response->getBody()->getContents();
                die;
            }
        }
        sleep(1);
        $html = $this->getRemote('/mod/quiz/summary.php?attempt=' . $attemptId . '&cmid=' . $id, 'summary-' . $attemptId . '-' . $i);

        $hForm = $html->find('form', 1);
        $data = [];
        $hInputs = $hForm->find('input[type=hidden]');
        foreach ($hInputs as $hInput) {
            if (!empty($hInput->name)) {
                $data[$hInput->name] = $hInput->value;
            }
        }
        $action = $hForm->action;
        $action = substr($action, 19);
        sleep(1);
        $response = $this->client->post($action, [
            'form_params' => $data, 'allow_redirects' => false
        ]);

        if ($response->getStatusCode() != 303) {
            echo 'Ошибка notatest (4) ' . $response->getStatusCode() . "\n\n\n";
            echo $response->getBody()->getContents();
            die;
        }

        return $attemptId;
    }


    public function getTickets() {
        if (!\Cache::has('notatest-courses')) {
            $html = $this->getRemote('/course/view.php?id=14', 'course-list');
            $pairs = [];
            /** @var simple_html_dom_node $htmlBlock */
            foreach ($html->find('.activityinstance') as $htmlBlock) {
                $src = $htmlBlock->find('a', 0)->href;

                $id = (int)mb_substr($src, mb_strpos($src, '?id=') + 4);
                $name = $htmlBlock->find('.instancename', 0)->text();
                $ticket = (int)mb_substr($name, 16);
                $pairs[$ticket] = $id;
            }
            \Cache::put('notatest-courses', $pairs);
        } else {
            $pairs = \Cache::get('notatest-courses');
        }

        return $pairs;
    }

    public function getAttemptId($id) {

        $html = $this->getRemote('/mod/quiz/view.php?id=' . $id, 'ticket-' . $id);
        $htmlForm = $html->find('.quizstartbuttondiv form', 0);

        if ($htmlForm) {
            $cmid = $htmlForm->find('[name=cmid]', 0)->value;
            $sesskey = $htmlForm->find('[name=sesskey]', 0)->value;

            $response = $this->client->post('/mod/quiz/startattempt.php', [
                'form_params' => [
                    'cmid' => $cmid,
                    'sesskey' => $sesskey,
                ], 'allow_redirects' => false
            ]);

            $attemptUrl = $response->getHeader('Location')[0];
            $p1 = strpos($attemptUrl, '?attempt=');
            $p2 = strpos($attemptUrl, 'cmid=');

            if ($p1 === false || $p2 === false || $p1 > $p2) {
                echo 'Ошибка notatest (1) ' . $attemptUrl . "\n\n\n";
                echo $response->getBody();
                die;
            }

            $attemptId = substr($attemptUrl, $p1 + 9, $p1 + 7 - $p2);

            if (empty($attemptId)) {
                echo 'Ошибка notatest (3) ' . "\n\n\n";
                die;
            }

            $this->autoClick($id, $attemptId);

            return $attemptId;

        } else {
            $htmlForm = $html->find('.singlebutton form', 0);

            if ($htmlForm) {
                $attemptId = $htmlForm->find('input[name=attempt]', 0)->value;
                echo $attemptId;
                return $attemptId;
            } else {
                echo 'Ошибка notatest (5)' . "\n\n\n";
                die;
            }
        }

    }

    public function getTicket($attempt, $ticketIndex, $cmid = '105') {

        $url = '/mod/quiz/review.php?attempt=' . $attempt . '&cmid=' . $cmid . '&showall=1';
        $html = $this->getRemote($url, 'attempt-' . $attempt);

        $htmlQues = $html->find('.que .content');

        $dbBlock = Block::find(4);

        $dbBlock->questions()->delete();

        /** @var simple_html_dom_node $htmlQue */
        foreach ($htmlQues as $index => $htmlQue) {

            // dd($htmlQue->find('.formulation .qtext p',1)->text());

            $els = $htmlQue->find("*");
            foreach ($els as $el) {
                $el->removeAttribute('id');
            }

            $htmlQueText = $htmlQue->find('.formulation .qtext', 0);

            $htmlQuePs = $htmlQueText->find('p');

            $que = [];
            if (count($htmlQuePs)) {
                /** @var simple_html_dom_node $htmlQueP */
                foreach ($htmlQuePs as $htmlQueP) {
                    $p = trim($htmlQueP->text());
                    if (!empty($p) && !in_array($p, $que)) {
                        $que[] = $p;
                    }
                }
                $que = join("\n", $que);
            }
            if (empty($que)) {
                $que = trim($htmlQue->text());
            }

            // $this->line($que);

            $htmlAnsws = $htmlQue->find('.answer label');


            $answs = [];
            /** @var simple_html_dom_node $htmlAnsw */
            foreach ($htmlAnsws as $htmlAnsw) {
                $answ = $htmlAnsw->text();
                $number = $htmlAnsw->find('.answernumber', 0)->text();
                $answ = trim(mb_substr($answ, strlen($number)));
                $answs[] = $answ;
                // $this->line("\t" . $answ);
            }
            $description = $htmlQue->find('.generalfeedback', 0);
            if($description){
                $description = '<p>' . trim($description->text()) . '</p>';
            } else {
                $description = null;
            }
            $right = trim($htmlQue->find('.rightanswer', 0)->text());
            $rightIndex = -1;
            if (!empty($right) && \Str::startsWith($right, 'Правильный ответ: ')) {
                $right = mb_substr($right, 18);
                $rightIndex = array_search($right, $answs);
                if ($rightIndex === false) {
                    $rightIndex = null;
                    $this->warn("Вопрос " . ($index + 1) . " имеет некорректный верный ответ (1)");
                }
            } else {
                $rightIndex = null;
                $this->warn("Вопрос " . ($index + 1) . " имеет некорректный верный ответ (2)");
            }

            if (count($answs) == 0) {
                $this->warn("Вопрос " . ($index + 1) . " не содержит ответов");
            }

            $dbQuestion = $dbBlock->questions()->create([
                'order' => $index + 1,
                'ticket' => $ticketIndex,
                'text' => $que,
                'options' => $answs,
                'right' => $rightIndex,
                'description' => $description
            ]);


        }
    }


    public function getRemote($url, $cacheKey = false) {

        $response = $this->client->get($url);
        if ($response->getStatusCode() != 200) {
            echo 'Ошибка notatest ' . $response->getStatusCode() . "\n\n\n";
            echo $response->getBody();
            die;
        }
        $body = $response->getBody()->getContents();

        return HtmlDomParser::str_get_html($body);
    }

    public function clearCache($cacheKey) {
        $fileName = '/temp/notter/' . $cacheKey . '.html';
        if (\Storage::exists($fileName)) {
            \Storage::delete($fileName);
        }
    }

}
