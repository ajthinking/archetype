tell application "System Events"
    try
        key down option
        key down shift
        
        key code 26

        key up option
        key up shift
        key up option
    on error
        key up {option, shift}
    end try
end tell