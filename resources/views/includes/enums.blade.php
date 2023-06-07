{{-- Enum定義 --}}
<script type="text/javascript">
const Flags = new Enum(@json(\App\Flags::to_json()));
const Weekdays = new Enum(@json(\App\Weekdays::to_json()));
const Genders = new Enum(@json(\App\Genders::to_json()));
const ReserveTypes = new Enum(@json(\App\ReserveTypes::to_json()));
const AffiliationDetailTypes = new Enum(@json(\App\AffiliationDetailTypes::to_json()));
const DishTypes = new Enum(@json(\App\DishTypes::to_json()));
const MenuItemTypes = new Enum(@json(\App\MenuItemTypes::to_json()));
</script>
<script type="text/javascript">
dayjs.prototype.weekday = function() {
    const weekdays = [
        Weekdays.SUNDAY,
        Weekdays.MONDAY,
        Weekdays.TUESDAY,
        Weekdays.WEDNESDAY,
        Weekdays.THURSDAY,
        Weekdays.FRIDAY,
        Weekdays.SATURDAY
    ];
    return weekdays[this.day()];
}
</script>
