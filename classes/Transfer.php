<?php 

namespace BankAPI;
use mysqli;
use Exception;

class Transfer {

    public static function new(int $source, int $target, int $amount, mysqli $db ) : void {

        //rozpocznij transakcję
        $db->begin_transaction();
        try{
            //aktualizuj stan konta źródłowego
            $sql = "UPDATE konto SET konto.money_value = konto.money_value - ? WHERE konto.ID = ?";
            //przygotuj zapytanie
            $query = $db->prepare($sql);
            //podaj wartości do zapytania
            $query->bind_param('ii', $amount, $source);
            //wykonaj zapytanie
            $query->execute();
            //aktualizuj stan konta docelowego
            $sql = "UPDATE konto SET konto.rachunek_value = konto.rachunek_value - ? WHERE konto.ID = ?";
            //przygotuj zapytanie
            $query = $db->prepare($sql);
            //podaj wartości do zapytania
            $query->bind_param('ii', $amount, $source);
            //wykonaj zapytanie
            $query->execute();
            //aktualizuj stan konta docelowego
            $sql = "UPDATE konto SET konto.money_value = konto.money_value + ? WHERE konto.ID = ?";
            //przygotuj zapytanie
            $query = $db->prepare($sql);
            //podaj wartości do zapytania
            $query->bind_param('ii', $amount, $target);
            if ($amount < 0 || $amount == 0){
                $db->rollback();
                throw new Exception('błąd przelewu, wartość nie jest poprawna');
            }
            //wykonaj zapytanie
            $query->execute();
            $sql = "UPDATE konto SET konto.rachunek_value = konto.rachunek_value + ? WHERE konto.ID = ?";
            //przygotuj zapytanie
            $query = $db->prepare($sql);
            //podaj wartości do zapytania
            $query->bind_param('ii', $amount, $target);
            if ($amount < 0){
                $db->rollback();
                throw new Exception('błąd przelewu, wartość nie jest zgodna z zasadami');
            }
            //wykonaj zapytanie
            $query->execute();
            // zapisz dane o transferze
            $sql = "INSERT INTO transfer_usser( transfer_usser.source_usser ,  transfer_usser.target_usser , transfer_usser.amount_transfer) VALUES (?, ?, ?)";
            //przygotuj zapytanie
            $query = $db->prepare($sql);
            if ($amount < 0){
                $db->rollback();
                throw new Exception('zbyt mało pieniędzy na koncie');
            }
            //podaj wartości do zapytania
            $query->bind_param('iii', $source, $target, $amount);
            //wykonaj zapytanie
            $query->execute();

            //zakończ transakcję
            $db->commit();
        }
        //jeśli wystąpi błąd
        catch (Exception $e) {
            //wycofaj transakcję
            $db->rollback();
            //wyświetl błąd
            throw new Exception('Transfer failed');
        }
    }
        

}

?>